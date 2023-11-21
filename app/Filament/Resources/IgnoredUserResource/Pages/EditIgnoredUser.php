<?php

namespace App\Filament\Resources\IgnoredUserResource\Pages;

use App\Filament\Resources\IgnoredUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIgnoredUser extends EditRecord
{
    protected static string $resource = IgnoredUserResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
