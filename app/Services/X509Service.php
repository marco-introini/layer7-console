<?php

namespace App\Services;

use Carbon\Carbon;

class X509Service
{
    /**
     * @return array<string,string>
     */
    public static function generateCertificate(string $commonName, ?Carbon $expirationDate): array
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'dn' => [
                'countryName' => 'IT',
                'stateOrProvinceName' => 'State',
                'localityName' => 'City',
                'organizationName' => 'Organization',
                'organizationalUnitName' => 'Organizational Unit',
                'commonName' => 'Your Common Name',
                'emailAddress' => 'email@example.com',
                'serialNumber' => 1234, // The serial number
            ],
        ];

        $privateKeyResource = openssl_pkey_new($config);
        openssl_pkey_export($privateKeyResource, $privateKey);

        $csr = openssl_csr_new($config['dn'], $privateKeyResource);

        $certificateResource = openssl_csr_sign($csr, null, $privateKeyResource, 365, $config);
        $certificateDetails = openssl_x509_parse($certificateResource);
        $validFrom = Carbon::createFromTimestamp($certificateDetails['validFrom_time_t']);
        $validTo = Carbon::createFromTimestamp($certificateDetails['validTo_time_t']);
        echo $validFrom.' - '.$validTo;

        openssl_x509_export($certificateResource, $certificate);

        return [$privateKey, $certificate];
    }
}
