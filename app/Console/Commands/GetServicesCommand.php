<?php

namespace App\Console\Commands;

use App\Exceptions\GatewayConnectionException;
use App\Helpers\XmlHelper;
use App\Models\Gateway;
use App\Models\Service;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;
use function Laravel\Prompts\error;
use function Laravel\Prompts\outro;

class GetServicesCommand extends Command
{
    protected $signature = 'gateway:get-services';

    protected $description = 'Get all services form API Gateway';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            try {
                $serviceList = $gateway->getGatewayResponse('/restman/1.0/services');
            } catch (GatewayConnectionException $e) {
                error('Error obtaining services: '.$e->getConnectionError());
                continue;
            }

            Service::where('gateway_id', '=', $gateway->id)->delete();


            $numberOfServices = 0;
            $numberOfBackends = 0;

            foreach ($serviceList['l7:List']['l7:Item'] as $gwService) {

                $serviceName = $gwService['l7:Name'];
                $serviceId = $gwService['l7:Id'];
                $serviceDetail = $gwService['l7:Resource']['l7:Service']['l7:ServiceDetail'];
                $urlPattern = $serviceDetail['l7:ServiceMappings']['l7:HttpMapping']['l7:UrlPattern'];

                $serviceModel = Service::create([
                    'gateway_id' => $gateway->id,
                    'name' => $serviceName,
                    'gateway_service_id' => $serviceId,
                    'url' => $urlPattern,
                ]);

                try {
                    $serviceResponseDetail = $gateway->getGatewayResponse('/restman/1.0/services/'.$serviceId);
                    try {
                        $details = XmlHelper::xml2array($serviceResponseDetail['l7:Item']['l7:Resource']['l7:Service']['l7:Resources']['l7:ResourceSet'][0]['l7:Resource']);
                        $backend = $details['wsp:Policy']['wsp:All']['L7p:HttpRoutingAssertion']['L7p:ProtectedServiceUrl_attr']['stringValue'];
                        $numberOfBackends++;
                    } catch (Exception) {
                        try {
                            $details = XmlHelper::xml2array($serviceResponseDetail['l7:Item']['l7:Resource']['l7:Service']['l7:Resources']['l7:ResourceSet']['l7:Resource']);
                            $backend = $details['wsp:Policy']['wsp:All']['L7p:HttpRoutingAssertion']['L7p:ProtectedServiceUrl_attr']['stringValue'];
                            $numberOfBackends++;
                        } catch (Exception) {
                            error("Error getting details for $serviceName (ID $serviceId)");
                            Log::error("Error getting details for $serviceName (ID $serviceId)");
                        }
                    }

                    if (isset($backend)) {
                        $serviceModel->backends = [
                            'backend' => $backend,
                        ];
                        $serviceModel->save();
                    }

                } catch (GatewayConnectionException $e) {
                    error("Error obtaining service details for $serviceName - $serviceId: ".$e->getConnectionError());
                    continue;
                }

                $numberOfServices++;
            }

            Log::info("Imported $numberOfServices services and $numberOfBackends backends from API Gateway");
            outro("Imported $numberOfServices services and $numberOfBackends backends from API Gateway");

            if (App::environment('production')) {
                SlackAlert::message("Imported $numberOfServices services and $numberOfBackends backends from API Gateway");
            }

        }
    }
}
