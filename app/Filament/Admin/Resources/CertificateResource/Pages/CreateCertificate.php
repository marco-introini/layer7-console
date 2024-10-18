<?php

namespace App\Filament\Admin\Resources\CertificateResource\Pages;

use App\Enumerations\CertificateRequestStatus;
use App\Filament\Admin\Resources\CertificateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificate extends CreateRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = CertificateRequestStatus::REQUESTED;

        return $data;
    }
}
