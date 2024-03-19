<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Helpers\XmlHelper;
use App\Models\Certificate;
use App\Models\GatewayUser;
use App\Models\IgnoredUser;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;
use function Laravel\Prompts\info;

// Import DB

class GetGatewayUsersCommand extends Command
{
    protected $signature = 'gateway:get-users';

    protected $description = 'Get all users and certificates form API Gateway';

    public function handle(): void
    {
        $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
            ->get('https://'.config('apigw.hostname').'/restman/1.0/identityProviders/'.config('apigw.identityProvider').'/users');

        $userList = XmlHelper::xml2array($response->body());
        $number = 0;

        // Truncate GatewayUser table
        DB::table('gateway_users')->truncate();

        foreach ($userList['l7:List']['l7:Item'] as $user) {
            $userId = $user['l7:Id'];
            if (IgnoredUser::where('gateway_id', 'like', $userId)->exists()) {
                continue;
            }

            $username = $user['l7:Name'];
            $detailUri = $user['l7:Link_attr']['uri'];

            $this->info('Getting info for '.$userId);
            $response = Http::withBasicAuth(config('apigw.user'), config('apigw.password'))
                ->get(
                    'https://'.config('apigw.hostname').'/restman/1.0/identityProviders/'.config('apigw.identityProvider').'/users/'.$userId.'/certificate'
                );

            $certificate = XmlHelper::xml2array($response->body())['l7:Item']['l7:Resource']['l7:CertificateData']['l7:Encoded'];

            if ($certificate === '') {
                Log::warning("Certificate not found for $userId");
                $this->warn("Certificate not found for $userId");
                continue;
            }

            $certVO = CertificateVO::fromLayer7EncodedCertificate($certificate);

            $certificate = Certificate::createOrFirst([
                'type' => CertificateType::USER_CERTIFICATE,
                'common_name' => $certVO->commonName,
                'valid_from' => $certVO->validFrom,
                'valid_to' => $certVO->validTo,
            ]);

            GatewayUser::updateOrInsert([
                'gateway_id' => $userId,
            ],
                [
                    'username' => $username,
                    'detail_uri' => $detailUri,
                    'certificate_id' => $certificate->id,
                ]);

            $number++;
        }

        info("Found $number users");
        Log::info("Imported $number users from API Gateway");

        if (App::environment('production')) {
            SlackAlert::message("Imported $number users from Layer7 API Gateway");
        }

    }
}
