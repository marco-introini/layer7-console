<?php

namespace App\Filament\Resources\GatewayuserResource\Pages;

use App\Filament\Resources\GatewayUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGatewayUser extends EditRecord
{
    protected static string $resource = GatewayUserResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
