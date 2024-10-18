<?php

namespace App\Filament\Admin\Resources\IgnoredUserResource\Pages;

use App\Filament\Admin\Resources\IgnoredUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageIgnoredUsers extends ManageRecords
{
    protected static string $resource = IgnoredUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
