<?php

namespace App\Helpers;

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
}
