<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Exceptions\GatewayConnectionException;
use App\Models\Gateway;
use App\Models\GatewayCertificate;
use App\Models\GatewayUser;
use App\Models\IgnoredUser;
use App\ValueObjects\CertificateVO;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;

// Import DB

class GetGatewayUsersCommand extends Command
{
    protected $signature = 'gateway:get-users';

    protected $description = 'Get all users and certificates form API Gateways';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            info("Get all users from $gateway->name API Gateway");

            try {
                $userList = $gateway->getGatewayResponse('/restman/1.0/identityProviders/'.$gateway->identity_provider.'/users');
            } catch (GatewayConnectionException $e) {
                error('Error obtaining users details: '.$e->getConnectionError());

                continue;
            }
            $number = 0;
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
                try {
                    $detailsResponse = $gateway->getGatewayResponse('/restman/1.0/identityProviders/'.$gateway->identity_provider.'/users/'.$userId.'/certificate');
                } catch (GatewayConnectionException $e) {
                    error("Error obtaining user certificate details for $userId: ".$e->getConnectionError());

                    continue;
                }

                $certificate = $detailsResponse['l7:Item']['l7:Resource']['l7:CertificateData']['l7:Encoded'];

                if ($certificate === '') {
                    Log::warning("Certificate not found for $userId");
                    $this->warn("Certificate not found for $userId");

                    continue;
                }

                $certVO = CertificateVO::fromLayer7EncodedCertificate($certificate);

                $certificate = GatewayCertificate::createOrFirst([
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
                        'gateway_certificate_id' => $certificate->id,
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
