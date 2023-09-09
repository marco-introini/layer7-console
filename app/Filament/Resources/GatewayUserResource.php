<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GatewayuserResource\Pages;
use App\Models\GatewayUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GatewayUserResource extends Resource
{
    protected static ?string $model = GatewayUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = "API Gateway User";
    protected static ?string $pluralLabel = "API Gateway Users";

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username'),
                Forms\Components\TextInput::make('common_name'),
                Forms\Components\TextInput::make('valid_from'),
                Forms\Components\TextInput::make('valid_to'),
                Forms\Components\TextInput::make('userid'),
                Forms\Components\Placeholder::make(''),
                Forms\Components\TextInput::make('detail_uri')->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->sortable()
                    ->searchable(),
                BooleanColumn::make('valid')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->label('Valido'),
                BooleanColumn::make('scadenza')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->label("Verifica Scadenza"),
                TextColumn::make('valid_to')
                    ->sortable()
                    ->label('Data Scadenza'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Dettagli'),
            ])
            ->bulkActions([
            ])
            ->defaultSort('valid_to');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGatewayUser::route('/'),
        ];
    }
}
