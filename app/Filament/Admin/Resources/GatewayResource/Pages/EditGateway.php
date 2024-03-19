<?php

namespace App\Filament\Admin\Resources\GatewayResource\Pages;

use App\Filament\Admin\Resources\GatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGateway extends EditRecord
{
    protected static string $resource = GatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
