<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetTrustedCertsCommand extends Command
{

    protected $signature = 'gateway:get-trusted-certs';

    protected $description = 'Get Layer7 Trusted Certificates';

    public function handle()
    {
        //
    }
}
