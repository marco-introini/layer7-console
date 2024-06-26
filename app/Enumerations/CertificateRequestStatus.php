<?php

namespace App\Enumerations;

enum CertificateRequestStatus: string
{
    case REQUESTED = 'Requested';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';
    case ISSUED = 'Issued';
}
