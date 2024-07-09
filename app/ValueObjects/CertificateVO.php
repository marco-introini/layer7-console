<?php

namespace App\ValueObjects;

use App\Services\GatewayCertificateService;
use Carbon\Carbon;

readonly class CertificateVO
{

    private function __construct(public string $commonName, public Carbon $validFrom, public Carbon $validTo)
    {
    }

    public static function fromLayer7EncodedCertificate(string $encodedCert): self
    {
        $certificateDer = base64_decode($encodedCert);

        $info = openssl_x509_parse(GatewayCertificateService::der2pem($certificateDer));

        $cn = $info['subject']['CN'] ?? 'CN NOT FOUND';

        $valid_from = date(DATE_RFC2822, $info['validFrom_time_t']);
        $valid_to = date(DATE_RFC2822, $info['validTo_time_t']);

        // prima usavo make() -> controllare
        return new self($cn, Carbon::parse($valid_from), Carbon::parse($valid_to));
    }
}
