<?php

namespace App\Filament\Admin\Resources\GatewayCertificateResource\Pages;

use App\Filament\Admin\Resources\GatewayCertificateResource;
use Filament\Resources\Pages\ViewRecord;

class ViewGatewayCertificate extends ViewRecord
{
    protected static string $resource = GatewayCertificateResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
