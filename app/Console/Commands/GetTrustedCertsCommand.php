<?php

namespace App\Console\Commands;

use App\Http\Helpers\XmlHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetTrustedCertsCommand extends Command
{

    protected $signature = 'gateway:get-trusted-certs';

    protected $description = 'Get Layer7 Trusted Certificates';

    public function handle(): void
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get('https://'.config('apigw.hostname').'/restman/1.0/trustedCertificates');

        $certs = XmlHelper::xml2array($response->body());

        foreach ($certs['l7:List']['l7:Item'] as $cert) {
        }

    }
}
