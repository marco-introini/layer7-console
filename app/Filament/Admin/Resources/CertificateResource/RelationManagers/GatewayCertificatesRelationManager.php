<?php

namespace App\Filament\Admin\Resources\CertificateResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class GatewayCertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'gatewayCertificates';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('common_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('common_name')
            ->columns([
                Tables\Columns\TextColumn::make('common_name'),
                Tables\Columns\TextColumn::make('gateway.name'),
                Tables\Columns\TextColumn::make('valid_from'),
                Tables\Columns\TextColumn::make('valid_to'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
