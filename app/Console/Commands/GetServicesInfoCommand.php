<?php

namespace App\Console\Commands;

use App\Http\Helpers\XmlHelper;
use App\Models\Service;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

use function Laravel\Prompts\error;

class GetServicesInfoCommand extends Command
{
    protected $signature = 'gateway:services';

    protected $description = 'Get all services form API Gateway';

    public function handle(): void
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get('https://'.config('apigw.hostname').'/restman/1.0/services');

        //dump($response->body());

        DB::table('services')->truncate();

        $serviceList = XmlHelper::xml2array($response->body());

        $number = 0;

        foreach ($serviceList['l7:List']['l7:Item'] as $gwService) {

            $serviceName = $gwService['l7:Name'];
            $serviceId = $gwService['l7:Id'];
            $serviceDetail = $gwService['l7:Resource']['l7:Service']['l7:ServiceDetail'];
            $urlPattern = $serviceDetail['l7:ServiceMappings']['l7:HttpMapping']['l7:UrlPattern'];

            $serviceModel = Service::create([
                'name' => $serviceName,
                'gateway_id' => $serviceId,
                'url' => $urlPattern,
            ]);

            $responseDetails = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
                ->get('https://'.config('apigw.hostname').'/restman/1.0/services/'.$serviceId);

            $serviceResponseDetail = XmlHelper::xml2array($responseDetails->body());

            try {
                $dettagli = XmlHelper::xml2array($serviceResponseDetail['l7:Item']['l7:Resource']['l7:Service']['l7:Resources']['l7:ResourceSet'][0]['l7:Resource']);
                $backend = $dettagli['wsp:Policy']['wsp:All']['L7p:HttpRoutingAssertion']['L7p:ProtectedServiceUrl_attr']['stringValue'];
            } catch (Exception) {
                try {
                    $dettagli = XmlHelper::xml2array($serviceResponseDetail['l7:Item']['l7:Resource']['l7:Service']['l7:Resources']['l7:ResourceSet']['l7:Resource']);
                    $backend = $dettagli['wsp:Policy']['wsp:All']['L7p:HttpRoutingAssertion']['L7p:ProtectedServiceUrl_attr']['stringValue'];
                } catch (Exception) {
                    error("Error getting details for {$serviceName} (ID {$serviceId})");
                }
            }

            if (isset($backend)) {
                $serviceModel->backends = [
                    'backend' => $backend,
                ];
                $serviceModel->save();
            }

            $number++;
        }

        Log::info("Imported $number services from API Gateway");

        if (App::environment('production')) {
            SlackAlert::message("Imported $number services from API Gateway");
        }

    }
}
