<?php

namespace App\Filament\Resources\IgnoredUserResource\Pages;

use App\Filament\Resources\IgnoredUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIgnoredUsers extends ListRecords
{
    protected static string $resource = IgnoredUserResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
