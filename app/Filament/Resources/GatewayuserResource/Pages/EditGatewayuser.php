<?php

namespace App\Filament\Resources\GatewayuserResource\Pages;

use App\Filament\Resources\GatewayuserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGatewayuser extends EditRecord
{
    protected static string $resource = GatewayuserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
