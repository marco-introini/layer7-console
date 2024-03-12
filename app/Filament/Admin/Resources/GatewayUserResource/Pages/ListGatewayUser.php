<?php

namespace App\Filament\Admin\Resources\GatewayUserResource\Pages;

use App\Filament\Admin\Resources\GatewayUserResource;
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
