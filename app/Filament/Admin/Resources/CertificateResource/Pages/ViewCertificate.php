<?php

namespace App\Filament\Admin\Resources\CertificateResource\Pages;

use App\Filament\Admin\Resources\CertificateResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificate extends ViewRecord
{

    protected static string $resource = CertificateResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }

}
