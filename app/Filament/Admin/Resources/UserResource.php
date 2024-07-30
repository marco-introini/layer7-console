<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers\CertificatesRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->required(),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name'),
                Forms\Components\Section::make('Visibility')
                    ->schema([
                        Forms\Components\Toggle::make('super_admin'),
                        Forms\Components\Toggle::make('admin'),
                    ])->visible(fn () => auth()->user()->super_admin),
                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn (User $record) => $record->created_at->diffForHumans()),
                        Forms\Components\Placeholder::make('updated_at')
                            ->content(fn (User $record) => $record->created_at->diffForHumans()),
                        Forms\Components\Placeholder::make('email_verified_at')
                            ->content(fn (User $record) => $record->created_at->diffForHumans()),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('company.name'),
                Tables\Columns\IconColumn::make('super_admin'),
                Tables\Columns\IconColumn::make('admin'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CertificatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
