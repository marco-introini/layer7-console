<?php

namespace App\Console\Commands;

use App\Http\Helpers\CertificateHelper;
use App\Http\Helpers\XmlHelper;
use App\Models\GatewayUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class GetGatewayUsersInfoCommand extends Command
{
    protected $signature = 'gateway:users';

    protected $description = 'Get all users and certificates form API Gateway';

    public function handle(): void
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get("https://".config('apigw.hostname')."/restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users");

        //preg_match_all("|([^\n]+.*<l7:User.*)|", $response->body(), $out);

        $listaUtenti = XmlHelper::xml2array($response->body());

        $number = 0;

        foreach ($listaUtenti['l7:List']['l7:Item'] as $utente) {
            $userId = $utente['l7:Id'];
            //$userId = str_replace('">', "", substr($line, strpos($line, "id=") + 4, null));
            if (GatewayUser::where('userid', value: $userId)
                ->where('ignored', value: '1')
                ->exists()) {
                Log::info("{$userId} is present and disabled");
                continue;
            };
            if (($userId === "00000000000000000000000000000003")  // admin
                || ($userId === "da2b9b245c56435a4ae977ac0cc3c47a") // mint_migration
            ) {
                continue;
            }
            $username = $utente['l7:Name'];
            $detailUri = $utente['l7:Link_attr']['uri'];

            $this->info("Getting info for ".$userId);
            $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
                ->get(
                    'https://'.config('apigw.hostname').'/restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users/'.$userId.'/certificate'
                );

            //$fromCertificateToEnd = substr($response->body(), strpos($response->body(), "<l7:Encoded>") + 12, null);
            //$certificate = substr($fromCertificateToEnd, 0, strpos($fromCertificateToEnd, "</l7:Encoded>"));

            $certificate = XmlHelper::xml2array($response->body())['l7:Item']['l7:Resource']['l7:CertificateData']['l7:Encoded'];

            //dd($certificate);
            if ($certificate === "") {
                Log::warning("Certificate not found for {$userId}");
                $this->warn("Certificate not found for {$userId}");
                continue;
            }
            $certificateDer = base64_decode($certificate);

            $info = openssl_x509_parse(CertificateHelper::der2pem($certificateDer));

            try {
                $cn = $info["subject"]["CN"];
            } catch (Exception $e) {
                Log::error("CN not found for {$userId}");
                $this->error("CN not found for {$userId}");
                $cn = "NOT FOUND";
            }


            $valid_from = date(DATE_RFC2822, $info['validFrom_time_t']);
            $valid_to = date(DATE_RFC2822, $info['validTo_time_t']);

            GatewayUser::updateOrInsert([
                'userid' => $userId,
            ],
                [
                    'username' => $username,
                    'detail_uri' => $detailUri,
                    'common_name' => $cn,
                    'valid_from' => Carbon::make($valid_from),
                    'valid_to' => Carbon::make($valid_to)
                ]);

            $number++;
        }

        $this->info("Found $number users");
        Log::info("Imported $number users from API Gateway");

        if (App::environment('production')) {
            SlackAlert::message("Imported $number users from Layer7 API Gateway");
        }

    }


}
