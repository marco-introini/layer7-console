<?php

namespace App\Console\Commands;

use App\Http\Helpers\CertificateHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetCertificateInfoCommand extends Command
{
    protected $signature = 'certificates:get';

    protected $description = 'Get all certificates form API Gateway';

    public function handle()
    {
        $response = Http::withBasicAuth('admin', 'Viola_2013')
            ->get('https://apigwinbound.popso.it/restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users');

        //dump($response->body());

        preg_match_all("|([^\n]+.*<l7:User.*)|", $response->body(), $out);

        $number = 0;

        foreach ($out[0] as $line) {
            // prendo quello che c'è dopo id= e poi faccio replace degli ultimi caratteri >' con vuoto
            $userId = str_replace('">', "", substr($line, strpos($line, "id=") + 4, null));
            echo "Getting info for ".$userId."\n";
            $response = Http::withBasicAuth('admin', 'Viola_2013')
                ->get(
                    'https://apigwinbound.popso.it/restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users/'.$userId.'/certificate'
                );

            $fromCertificateToEnd = substr($response->body(), strpos($response->body(), "<l7:Encoded>") + 12, null);
            $certificate = substr($fromCertificateToEnd, 0, strpos($fromCertificateToEnd, "</l7:Encoded>"));


            $certificateDer = base64_decode($certificate);

            $info = openssl_x509_parse(CertificateHelper::der2pem($certificateDer));

            dd($info);
            $number++;
        }


        echo "Found $number records";
    }



}
