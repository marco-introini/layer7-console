<?php

namespace App\Console\Commands;

use App\Http\Helpers\CertificateHelper;
use App\Http\Helpers\XmlHelper;
use App\Models\GatewayUser;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\SlackAlerts\Facades\SlackAlert;

class GetServicesInfoCommand extends Command
{
    protected $signature = 'gateway:services';

    protected $description = 'Get all services form API Gateway';

    public function handle()
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get("https://".config('apigw.hostname')."/restman/1.0/services");

        //dump($response->body());

        DB::table('services')->truncate();

        $listaServizi = XmlHelper::xml2array($response->body());

        $number = 0;

        foreach($listaServizi['l7:List']['l7:Item'] as $servizio) {

            $serviceName = $servizio['l7:Name'];
            $serviceDetail = $servizio['l7:Resource']['l7:Service']['l7:ServiceDetail'];
            $urlEsposto = $serviceDetail['l7:ServiceMappings']['l7:HttpMapping']['l7:UrlPattern'];

            Service::create([
                'name' => $serviceName,
                'url' => $urlEsposto,
            ]);


            $number++;
        }

        echo "Caricati $number servizi";

        if (App::environment('production')) {
            SlackAlert::message("Caricati $number servizi da API Gateway su DB locale");
        }

    }



}
