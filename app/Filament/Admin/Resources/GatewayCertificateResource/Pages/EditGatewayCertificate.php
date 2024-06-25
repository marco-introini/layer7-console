<?php

namespace App\Filament\Admin\Resources\GatewayCertificateResource\Pages;

use App\Filament\Admin\Resources\GatewayCertificateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGatewayCertificate extends EditRecord
{
    protected static string $resource = GatewayCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
