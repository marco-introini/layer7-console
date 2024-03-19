<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Spatie\SlackAlerts\Facades\SlackAlert;

use function Laravel\Prompts\info;

class CheckCertificatesCommand extends Command
{
    protected $signature = 'certificates:check';

    protected $description = 'Check all certificates from API Gateways';

    public function handle(): void
    {
        $expiredCerts = [];
        $expiringCerts = [];

        foreach (Certificate::all() as $certificate) {
            if (! $certificate->isValid()) {
                $expiredCerts[] = $certificate;

                continue;
            }
            if ($certificate->isExpiring()) {
                $expiringCerts[] = $certificate;
            }
        }

        $toSlack = $this->format('Expired Certificates', $expiredCerts).PHP_EOL;
        $toSlack .= $this->format('Expiring Certificates (in the next '.config('apigw.days_before_expiration').' days)',
            $expiringCerts);
        info($toSlack);
        if (App::environment('production')) {
            SlackAlert::message($toSlack);
        }
    }

    /**
     * @param  array<Certificate>  $certificates
     */
    private function format(string $title, array $certificates): string
    {
        $ret = $title.PHP_EOL.PHP_EOL;

        if (empty($certificates)) {
            $ret .= 'None'.PHP_EOL;
        }

        foreach ($certificates as $certificate) {
            $ret .= 'Gateway '.$certificate->gateway->name.' '.$certificate->common_name.' - Expire date: '.$certificate->valid_to.PHP_EOL;
        }

        return $ret;
    }
}
