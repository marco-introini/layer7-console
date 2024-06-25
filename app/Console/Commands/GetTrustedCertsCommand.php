<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Exceptions\GatewayConnectionException;
use App\Helpers\XmlHelper;
use App\Models\GatewayCertificate;
use App\Models\Gateway;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;

class GetTrustedCertsCommand extends Command
{
    protected $signature = 'gateway:get-trusted-certs';

    protected $description = 'Get Layer7 Trusted Certificates';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            info("Getting Trusted Certificates form $gateway->name gateway");
            try {
                $response = $gateway->getGatewayResponse('/restman/1.0/trustedCertificates');
            } catch (GatewayConnectionException $e) {
                error('Error obtaining certificate details: '.$e->getConnectionError());

                continue;
            }

            $certs = XmlHelper::findValuesOfKey($response, 'l7:TrustedCertificate');

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
            }
        }

    }
}
