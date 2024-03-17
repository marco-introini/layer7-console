<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\IgnoredUserResource\Pages;
use App\Filament\Resources\IgnoredUserResource\RelationManagers;
use App\Models\IgnoredUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IgnoredUserResource extends Resource
{
    protected static ?string $model = IgnoredUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gateway_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gateway_id'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\IgnoredUserResource\Pages\ManageIgnoredUsers::route('/'),
        ];
    }
}
