<?php

namespace App\Filament\Admin\Resources\GatewayCertificateResource\Pages;

use App\Filament\Admin\Resources\GatewayCertificateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGatewayCertificate extends CreateRecord
{
    protected static string $resource = GatewayCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
