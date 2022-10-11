<?php

namespace App\Console\Commands;

use App\Http\Helpers\CertificateHelper;
use App\Models\Gatewayuser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Spatie\SlackAlerts\Facades\SlackAlert;

class GetCertificateNotificationCommand extends Command
{
    protected $signature = 'certificates:check';

    protected $description = 'Check all certificates form API Gateway';

    public function handle()
    {
        $scaduti = [];
        $inScadenza = [];

        foreach (Gatewayuser::all() as $certificate) {
            if (!$certificate->valid) {
                $scaduti[] = $certificate;
                continue;
            }
            if (!$certificate->scadenza){
                $inScadenza[] = $certificate;
            }
        }

        $toSlack = $this->format("Certificati Scaduti",$scaduti).PHP_EOL;
        $toSlack .= $this->format("Certificati in Prossima Scadenza (entro ".config('apigw.giorni_anticipo_scadenza_certificati')." giorni)",$inScadenza);
        echo $toSlack;
        if (App::environment('production')) {
            SlackAlert::message($toSlack);
        }
    }

    private function format(string $title, array $certificates): string
    {
        $ret = $title.PHP_EOL.PHP_EOL;

        if (empty($certificates)){
            $ret .= "Nessuno".PHP_EOL;
        }

        foreach ($certificates as $certificate) {
            $ret .= $certificate->common_name." - Scadenza: ".$certificate->valid_to.PHP_EOL;
        }

        return $ret;
    }

}