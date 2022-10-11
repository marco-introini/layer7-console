<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
//use App\Filament\Resources\CertificateResource\RelationManagers;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('common_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('valid')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->label('Valido'),
                Tables\Columns\BooleanColumn::make('scadenza')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->label("Verifica Scadenza"),
                Tables\Columns\TextColumn::make('valid_from')
                    ->sortable()
                    ->label('Data Emissione'),
                Tables\Columns\TextColumn::make('valid_to')
                    ->sortable()
                    ->label('Data Scadenza'),
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }    
}
