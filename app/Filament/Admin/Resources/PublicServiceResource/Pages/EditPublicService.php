<?php

namespace App\Filament\Admin\Resources\PublicServiceResource\Pages;

use App\Filament\Admin\Resources\PublicServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicService extends EditRecord
{
    protected static string $resource = PublicServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
