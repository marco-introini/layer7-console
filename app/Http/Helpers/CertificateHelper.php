<?php

namespace App\Http\Helpers;

class CertificateHelper
{
    public static function der2pem($der_data)
    {
        $pem = chunk_split(base64_encode($der_data), 64, "\n");
        $pem = "-----BEGIN CER˙TIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
        return $pem;
    }

    public static function pem2der($pem_data)
    {
        $begin = "CERTIFICATE-----";
        $end = "-----END";
        $pem_data = substr($pem_data, strpos($pem_data, $begin) + strlen($begin));
        $pem_data = substr($pem_data, 0, strpos($pem_data, $end));
        $der = base64_decode($pem_data);
        return $der;
    }
}