<?php

namespace App\Filament\Resources\IgnoredUserResource\Pages;

use App\Filament\Resources\IgnoredUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIgnoredUser extends CreateRecord
{
    protected static string $resource = IgnoredUserResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
