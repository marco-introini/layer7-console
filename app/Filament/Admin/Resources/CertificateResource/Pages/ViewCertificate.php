<?php

namespace App\Filament\Admin\Resources\CertificateResource\Pages;

use App\Filament\Admin\Resources\CertificateResource;
use App\Models\Certificate;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificate extends ViewRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Approve')
                ->visible(fn (Certificate $record) => $record->isApprovable()),
            Actions\Action::make('Reject')
                ->visible(fn (Certificate $record) => $record->isApprovable()),
        ];
    }

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
