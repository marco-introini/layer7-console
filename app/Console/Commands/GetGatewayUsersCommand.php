<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Helpers\XmlHelper;
use App\Models\Certificate;
use App\Models\Gateway;
use App\Models\GatewayUser;
use App\Models\IgnoredUser;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

use function Laravel\Prompts\info;

// Import DB

class GetGatewayUsersCommand extends Command
{
    protected $signature = 'gateway:get-users';

    protected $description = 'Get all users and certificates form API Gateways';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $response = Http::withBasicAuth($gateway->admin_user, $gateway->admin_password)
                ->get('https://'.$gateway->host.'/restman/1.0/identityProviders/'.$gateway->identity_provider.'/users');

            $userList = XmlHelper::xml2array($response->body());
            $number = 0;

            info("Get all users from $gateway->name API Gateway");
            // remove all users of the current gateway
            GatewayUser::where('gateway_id', '=', $gateway->id)->delete();

            foreach ($userList['l7:List']['l7:Item'] as $user) {
                $userId = $user['l7:Id'];
                if (IgnoredUser::where('gateway_id', '=', $gateway->id)
                    ->where('gateway_user_id', 'like', $userId)
                    ->exists()) {
                    continue;
                }

                $username = $user['l7:Name'];
                $detailUri = $user['l7:Link_attr']['uri'];

                $this->info('Getting info for '.$userId);
                $response = Http::withBasicAuth($gateway->admin_user, $gateway->admin_password)
                    ->get(
                        'https://'.$gateway->host.'/restman/1.0/identityProviders/'.$gateway->identity_provider.'/users/'.$userId.'/certificate'
                    );

                $certificate = XmlHelper::xml2array($response->body())['l7:Item']['l7:Resource']['l7:CertificateData']['l7:Encoded'];

                if ($certificate === '') {
                    Log::warning("Certificate not found for $userId");
                    $this->warn("Certificate not found for $userId");

                    continue;
                }

                $certVO = CertificateVO::fromLayer7EncodedCertificate($certificate);

                $certificate = Certificate::createOrFirst([
                    'gateway_id' => $gateway->id,
                    'type' => CertificateType::USER_CERTIFICATE,
                    'common_name' => $certVO->commonName,
                    'valid_from' => $certVO->validFrom,
                    'valid_to' => $certVO->validTo,
                ]);

                GatewayUser::updateOrInsert([
                    'gateway_id' => $gateway->id,
                    'gateway_user_id' => $userId,
                ],
                    [
                        'gateway_id' => $gateway->id,
                        'username' => $username,
                        'detail_uri' => $detailUri,
                        'certificate_id' => $certificate->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                $number++;
            }

            info("Found $number users for $gateway->name");
            Log::info("Imported $number users from $gateway->name API Gateway");

            if (App::environment('production')) {
                SlackAlert::message("Imported $number users from $gateway->name Layer7 API Gateway");
            }

        }
    }
}
