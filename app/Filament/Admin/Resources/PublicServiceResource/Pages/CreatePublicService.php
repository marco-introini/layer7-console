<?php

namespace App\Filament\Admin\Resources\PublicServiceResource\Pages;

use App\Filament\Admin\Resources\PublicServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePublicService extends CreateRecord
{
    protected static string $resource = PublicServiceResource::class;

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
