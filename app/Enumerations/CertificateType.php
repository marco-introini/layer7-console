<?php

namespace App\Enumerations;

enum CertificateType: string
{
    case TRUSTED_CERT = 'Trusted Certificate';
    case PRIVATE_KEY = 'Private Key';

}
