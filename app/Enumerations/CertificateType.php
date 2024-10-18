<?php

namespace App\Enumerations;

enum CertificateType: string
{
    case TRUSTED_CERT = 'Trusted Certificate';
    case PRIVATE_KEY = 'Private Key';
    case USER_CERTIFICATE = 'User Certificate';

    /** @return array<string,string> */
    public static function associativeForFilamentFilter(): array
    {
        $values = array_column(self::cases(), 'value');
        $names = array_column(self::cases(), 'name');

        return array_combine($values, $names);
    }
}
