<?php

namespace App\Filament\Resources\GatewayuserResource\Pages;

use App\Filament\Resources\GatewayuserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGatewayuser extends ListRecords
{
    protected static string $resource = GatewayuserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
