<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Helpers\XmlHelper;
use App\Models\Certificate;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetTrustedCertsCommand extends Command
{

    protected $signature = 'gateway:get-trusted-certs';

    protected $description = 'Get Layer7 Trusted Certificates';

    public function handle(): void
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get('https://'.config('apigw.hostname').'/restman/1.0/trustedCertificates');

        $certs = XmlHelper::xml2array($response->body());
        $certs = $certs['l7:List']['l7:Item'];
        dump($certs);

        foreach ( $certs as $single) {
            dd($single);
            $name = $single['l7:Resource']['l7:TrustedCertificate']['l7:Name'];

            $certVO = CertificateVO::fromLayer7EncodedCertificate($single['l7:Resource']['l7:TrustedCertificate']['l7:CertificateData']['l7:Encoded']);

            Certificate::updateOrCreate(
                [
                    'common_name' => $certVO->commonName
                ], [
                    'type' => CertificateType::TRUSTED_CERT,
                    'common_name' => $certVO->commonName,
                    'gateway_id' => $name,
                    'valid_from' => $certVO->validFrom,
                    'valid_to' => $certVO->validTo,
                    'updated_at' => now(),
            ]);
        }

    }
}
