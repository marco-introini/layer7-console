<?php

namespace App\Filament\Resources\GatewayuserResource\Pages;

use App\Filament\Resources\GatewayUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGatewayUser extends ListRecords
{
    protected static string $resource = GatewayUserResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
