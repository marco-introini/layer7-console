<?php

namespace App\Filament\Admin\Resources\GatewayServiceResource\Pages;

use App\Filament\Admin\Resources\GatewayServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGatewayServices extends ListRecords
{
    protected static string $resource = GatewayServiceResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
