<?php

namespace App\Console\Commands;

use App\Models\GatewayUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Spatie\SlackAlerts\Facades\SlackAlert;

class GetCertificateNotificationCommand extends Command
{
    protected $signature = 'certificates:check';

    protected $description = 'Check all certificates from API Gateway';

    public function handle(): void
    {
        $numExpiredCerts = [];
        $numExpiringCerts = [];

        foreach (GatewayUser::all() as $gatewayUser) {
            if (! $gatewayUser->isValid()) {
                $numExpiredCerts[] = $gatewayUser;
                continue;
            }
            if ($gatewayUser->isExpiring()) {
                $numExpiringCerts[] = $gatewayUser;
            }
        }

        $toSlack = $this->format('Expired Certificates', $numExpiredCerts).PHP_EOL;
        $toSlack .= $this->format('Expiring Certificates (in the next '.config('apigw.days_before_expiration').' days)', $numExpiringCerts);
        echo $toSlack;
        if (App::environment('production')) {
            SlackAlert::message($toSlack);
        }
    }

    private function format(string $title, array $certificates): string
    {
        $ret = $title.PHP_EOL.PHP_EOL;

        if (empty($certificates)) {
            $ret .= 'None'.PHP_EOL;
        }

        foreach ($certificates as $certificate) {
            $ret .= $certificate->common_name.' - Expire date: '.$certificate->valid_to.PHP_EOL;
        }

        return $ret;
    }
}
