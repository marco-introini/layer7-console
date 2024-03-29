<?php

namespace App\Filament\Admin\Resources\ServiceResource\Pages;

use App\Filament\Admin\Resources\ServiceResource;
use App\Filament\Helpers\FilamentTabsHelper;
use Filament\Resources\Pages\ViewRecord;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

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
