<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GatewayUserResource\Pages;
use App\Models\GatewayUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GatewayUserResource extends Resource
{
    protected static ?string $model = GatewayUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'API Gateway User';

    protected static ?string $pluralLabel = 'API Gateway Users';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username'),
                Forms\Components\TextInput::make('certificate.common_name'),
                Forms\Components\TextInput::make('certificate.valid_from'),
                Forms\Components\TextInput::make('certificate.valid_to'),
                Forms\Components\TextInput::make('gateway_id'),
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
                Tables\Columns\IconColumn::make('certificate.valid')
                    ->boolean()
                    ->label('Valid'),
                Tables\Columns\IconColumn::make('expiration_date')
                    ->boolean()
                    ->label('Expiring'),
                TextColumn::make('valid_to')
                    ->sortable()
                    ->label('Expiration Data'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail Url'),
            ])
            ->bulkActions([
            ]);
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
            'index' => \App\Filament\Admin\Resources\GatewayUserResource\Pages\ListGatewayUser::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
