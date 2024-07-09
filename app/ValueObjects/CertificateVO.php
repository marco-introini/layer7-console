<?php

namespace App\ValueObjects;

use App\Services\CertificateService;
use Carbon\Carbon;

readonly class CertificateVO
{
    public function __construct(public string $commonName,
        public Carbon|null $validFrom,
        public Carbon|null $validTo,
        public string|null $pemCertificate) {}

    public static function fromLayer7EncodedCertificate(string $encodedCert): self
    {
        $certificateDer = base64_decode($encodedCert);
        $certificatePem = CertificateService::der2pem($certificateDer);

        $info = openssl_x509_parse(CertificateService::der2pem($certificateDer));

        $cn = $info['subject']['CN'] ?? 'CN NOT FOUND';

        $valid_from = date(DATE_RFC2822, $info['validFrom_time_t']);
        $valid_to = date(DATE_RFC2822, $info['validTo_time_t']);

        // prima usavo make() -> controllare
        return new self($cn, Carbon::parse($valid_from), Carbon::parse($valid_to), $certificatePem);
    }
}
