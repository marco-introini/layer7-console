<?php

namespace App\Services;

use App\ValueObjects\CertificateVO;
use App\ValueObjects\KeyPairVO;
use Carbon\Carbon;

class X509Service
{
    public static function generateCertificate(string $commonName, ?Carbon $expirationDate): KeyPairVO
    {
        $config = [
            'digest_alg' => config('x509-generator.cert_alg'),
            'private_key_bits' => config('x509-generator.cert_keybit'),
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'dn' => [
                'countryName' => config('x509-generator.cert_country'),
                'stateOrProvinceName' => config('x509-generator.cert_state'),
                'localityName' => config('x509-generator.cert_city'),
                'organizationName' => config('x509-generator.cert_organization_name'),
                'organizationalUnitName' => config('x509-generator.cert_organization_unit'),
                'commonName' => $commonName,
                'emailAddress' => config('x509-generator.cert_email'),
                'serialNumber' => self::generateSerialNumber(),
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

        return new KeyPairVO(
            $privateKey,
            new CertificateVO(commonName: $commonName,
                validFrom: $validFrom,
                validTo: $validTo,
                pemCertificate: $certificate),
        );
    }

    public static function generateSerialNumber($length = 16): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $serial = '';
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $serial .= $characters[$index];
        }

        return $serial;
    }
}
