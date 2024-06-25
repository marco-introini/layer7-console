<?php

namespace App\Filament\Admin\Resources\GatewayCertificateResource\Pages;

use App\Filament\Admin\Resources\GatewayCertificateResource;
use App\Filament\Helpers\FilamentTabsHelper;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGatewayCertificates extends ListRecords
{
    protected static string $resource = GatewayCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return FilamentTabsHelper::getTabsArray();
    }
}
