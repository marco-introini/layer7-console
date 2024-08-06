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

class GetTrustedCertsCommand extends Command
{
    protected $signature = 'gateway:get-trusted-certs';

    protected $description = 'Get Layer7 Trusted Certificates';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $number = 0;
            info("Getting Trusted Certificates form $gateway->name gateway");
            try {
                $response = $gateway->getGatewayResponse('/restman/1.0/trustedCertificates');
            } catch (GatewayConnectionException $e) {
                error('Error obtaining certificate details: '.$e->getConnectionError());

                continue;
            }

            $certs = XmlService::findValuesOfKey($response, 'l7:TrustedCertificate');

            foreach ($certs as $single) {
                $name = $single['l7:Name'];

                $certVO = CertificateVO::fromLayer7EncodedCertificate($single['l7:CertificateData']['l7:Encoded']);

                GatewayCertificate::updateOrCreate(
                    [
                        'gateway_id' => $gateway->id,
                        'common_name' => $certVO->commonName,
                    ], [
                        'type' => CertificateType::TRUSTED_CERT,
                        'common_name' => $certVO->commonName,
                        'gateway_cert_id' => $name,
                        'valid_from' => $certVO->validFrom,
                        'valid_to' => $certVO->validTo,
                        'updated_at' => now(),
                    ]);

                info("Found certificate $name");
                $number++;
            }

            if (App::environment('production')) {
                SlackAlert::message("Imported $number trusted certificates from $gateway->name API Gateway");
            }
        }

    }
}
