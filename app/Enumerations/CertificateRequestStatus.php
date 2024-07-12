<?php

namespace App\Enumerations;

enum CertificateRequestStatus: string
{
    case REQUESTED = 'Requested';
    case REJECTED = 'Rejected';
    case ISSUED = 'Issued';
}
