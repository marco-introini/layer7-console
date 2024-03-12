<?php

namespace App\Filament\Admin\Resources\GatewayUserResource\Pages;

use App\Filament\Admin\Resources\GatewayUserResource;
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
