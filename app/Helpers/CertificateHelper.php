<?php

namespace App\Helpers;

use Carbon\Carbon;

class CertificateHelper
{
    public static function der2pem(string $der_data): string
    {
        $pem = chunk_split(base64_encode($der_data), 64, "\n");

        return "-----BEGIN CERTIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
    }

    public static function pem2der(string $pem_data): string
    {
        $begin = 'CERTIFICATE-----';
        $end = '-----END';
        $pem_data = substr($pem_data, strpos($pem_data, $begin) + strlen($begin));
        $pem_data = substr($pem_data, 0, strpos($pem_data, $end));

        return base64_decode($pem_data);
    }

    /**
     * @return array<string,string>
     */
    public static function generateCertificate(string $commonName, Carbon|null $expirationDate): array
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'dn' => [
                "countryName" => "IT",
                "stateOrProvinceName" => "State",
                "localityName" => "City",
                "organizationName" => "Organization",
                "organizationalUnitName" => "Organizational Unit",
                "commonName" => "Your Common Name",
                "emailAddress" => "email@example.com",
                'serialNumber' => 1234, // The serial number
            ]
        ];

        $privateKeyResource = openssl_pkey_new($config);
        openssl_pkey_export($privateKeyResource, $privateKey);

        $csr = openssl_csr_new($config['dn'], $privateKeyResource);

        $certificateResource = openssl_csr_sign($csr, null, $privateKeyResource, 365, $config);
        $certificateDetails = openssl_x509_parse($certificateResource);
        $validFrom = Carbon::createFromTimestamp($certificateDetails['validFrom_time_t']);
        $validTo = Carbon::createFromTimestamp($certificateDetails['validTo_time_t']);
        echo $validFrom. " - ".$validTo;

        openssl_x509_export($certificateResource, $certificate);

        return [$privateKey, $certificate];
    }

}
