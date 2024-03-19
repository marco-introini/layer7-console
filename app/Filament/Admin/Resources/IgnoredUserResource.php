<?php

namespace App\Filament\Admin\Resources;

use App\Models\IgnoredUser;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IgnoredUserResource extends Resource
{
    protected static ?string $model = IgnoredUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('gateway_id')
                    ->required()
                    ->relationship('gateway', 'name'),
                Forms\Components\TextInput::make('gateway_user_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gateway_user_id'),
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
