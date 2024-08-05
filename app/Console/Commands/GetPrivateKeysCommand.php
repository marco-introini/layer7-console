<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Exceptions\GatewayConnectionException;
use App\Models\Gateway;
use App\Models\GatewayCertificate;
use App\Services\XmlService;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Spatie\SlackAlerts\Facades\SlackAlert;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;

class GetPrivateKeysCommand extends Command
{
    protected $signature = 'gateway:get-private-keys';

    protected $description = 'Get Layer7 Private Keys Information';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $number = 0;
            info("Getting Private Keys form $gateway->name gateway");
            try {
                $response = $gateway->getGatewayResponse('/restman/1.0/privateKeys');
            } catch (GatewayConnectionException $e) {
                error('Error obtaining private keys: '.$e->getConnectionError());

                continue;
            }

            foreach ($response['l7:List']['l7:Item'] as $single) {
                $name = $single['l7:Name'];

                $multiCert = XmlService::findValuesOfKey($single['l7:Resource']['l7:PrivateKey'], 'l7:Encoded');

                foreach ($multiCert as $singleCert) {
                    $certVO = CertificateVO::fromLayer7EncodedCertificate($singleCert);

                    GatewayCertificate::updateOrCreate(
                        [
                            'gateway_id' => $gateway->id,
                            'common_name' => $certVO->commonName,
                        ], [
                        'type' => CertificateType::PRIVATE_KEY,
                        'common_name' => $certVO->commonName,
                        'gateway_cert_id' => $name,
                        'valid_from' => $certVO->validFrom,
                        'valid_to' => $certVO->validTo,
                        'updated_at' => now(),
                    ]);
                }

                info("Found private key $name");
                $number++;
            }
            if (App::environment('production')) {
                SlackAlert::message("Imported $number private keys from $gateway->name Layer7 API Gateway");
            }
        }
    }
}
