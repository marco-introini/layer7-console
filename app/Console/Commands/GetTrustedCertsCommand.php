<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Helpers\XmlHelper;
use App\Models\Certificate;
use App\Models\Gateway;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\info;

class GetTrustedCertsCommand extends Command
{
    protected $signature = 'gateway:get-trusted-certs';

    protected $description = 'Get Layer7 Trusted Certificates';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $response = Http::withBasicAuth($gateway->admin_user, $gateway->admin_password)
                ->get('https://'.$gateway->host.'/restman/1.0/trustedCertificates');

            $certs = XmlHelper::findValuesOfKey(XmlHelper::xml2array($response->body()),'l7:TrustedCertificate');

            foreach ($certs as $single) {
                $name = $single['l7:Name'];

                $certVO = CertificateVO::fromLayer7EncodedCertificate($single['l7:CertificateData']['l7:Encoded']);

                Certificate::updateOrCreate(
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
