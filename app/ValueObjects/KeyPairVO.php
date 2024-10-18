<?php

namespace App\ValueObjects;

readonly class KeyPairVO
{
    public function __construct(public string $privateKey, public CertificateVO $certificate) {}
}
