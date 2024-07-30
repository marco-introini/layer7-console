<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificates';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('requested_at')
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(Certificate $certificate): string => $certificate->getFilamentColor())
                    ->sortable(),
                Tables\Columns\TextColumn::make('publicService.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('requested_at')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }
}
