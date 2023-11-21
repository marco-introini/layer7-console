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
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class GetServicesInfoCommand extends Command
{
    protected $signature = 'gateway:services';

    protected $description = 'Get all services form API Gateway';

    public function handle(): void
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get("https://".config('apigw.hostname')."/restman/1.0/services");

        //dump($response->body());

        DB::table('services')->truncate();

        $serviceList = XmlHelper::xml2array($response->body());

        $number = 0;

        foreach($serviceList['l7:List']['l7:Item'] as $sservice) {

            $serviceName = $sservice['l7:Name'];
            $serviceDetail = $sservice['l7:Resource']['l7:Service']['l7:ServiceDetail'];
            $urlPattern = $serviceDetail['l7:ServiceMappings']['l7:HttpMapping']['l7:UrlPattern'];

            Service::create([
                'name' => $serviceName,
                'url' => $urlPattern,
            ]);


            $number++;
        }

        Log::info("Imported $number services from API Gateway");

        if (App::environment('production')) {
            SlackAlert::message("Imported $number services from API Gateway");
        }

    }



}
