<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetPrivateKeysCommand extends Command
{
    protected $signature = 'gateway:get-private-keys';

    protected $description = 'Get Layer7 Private Keys Information';

    public function handle(): void
    {

    }
}
