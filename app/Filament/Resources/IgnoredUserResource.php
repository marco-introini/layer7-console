<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IgnoredUserResource\Pages;
use App\Models\IgnoredUser;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IgnoredUserResource extends Resource
{
    protected static ?string $model = IgnoredUser::class;

    protected static ?string $slug = 'ignored-users';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('userid')
                ->required(),

            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn(?IgnoredUser $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn(?IgnoredUser $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('userid'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIgnoredUsers::route('/'),
            'create' => Pages\CreateIgnoredUser::route('/create'),
            'edit' => Pages\EditIgnoredUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
