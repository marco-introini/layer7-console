<?php

namespace App\ValueObjects;

use App\Services\CertificateService;
use Carbon\Carbon;

readonly class CertificateVO
{
    public function __construct(public string $commonName,
        public ?Carbon $validFrom,
        public ?Carbon $validTo,
        public ?string $pemCertificate) {}

    public static function fromLayer7EncodedCertificate(string $encodedCert): self
    {
        return self::fromPemCertificate(CertificateService::der2pem(base64_decode($encodedCert)));
    }

    public static function fromPemCertificate(string $pemData): self
    {
        $info = openssl_x509_parse($pemData);

        $cn = $info['subject']['CN'] ?? 'CN NOT FOUND';

        $valid_from = date(DATE_RFC2822, $info['validFrom_time_t']);
        $valid_to = date(DATE_RFC2822, $info['validTo_time_t']);

        return new self($cn, Carbon::parse($valid_from), Carbon::parse($valid_to), $pemData);
    }
}
