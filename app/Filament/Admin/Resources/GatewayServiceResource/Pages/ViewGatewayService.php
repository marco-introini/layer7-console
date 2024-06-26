<?php

namespace App\Filament\Admin\Resources\GatewayServiceResource\Pages;

use App\Filament\Admin\Resources\GatewayServiceResource;
use App\Filament\Helpers\FilamentTabsHelper;
use Filament\Resources\Pages\ViewRecord;

class ViewGatewayService extends ViewRecord
{
    protected static string $resource = GatewayServiceResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }

    public function getTabs(): array
    {
        return FilamentTabsHelper::getTabsArray();
    }
}
