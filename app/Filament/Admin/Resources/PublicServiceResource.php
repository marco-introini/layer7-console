<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PublicServiceResource\Pages;
use App\Filament\Admin\Resources\PublicServiceResource\RelationManagers;
use App\Models\PublicService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PublicServiceResource extends Resource
{
    protected static ?string $model = PublicService::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $slug = 'public-services';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                TextArea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('gateway_id')
                    ->required()
                    ->relationship('gateway', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('gatewayService.gateway.name'),
                TextColumn::make('gatewayService.name'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublicServices::route('/'),
            'create' => Pages\CreatePublicService::route('/create'),
            'edit' => Pages\EditPublicService::route('/{record}/edit'),
        ];
    }
}
