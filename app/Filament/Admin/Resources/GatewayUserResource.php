<?php

namespace App\Filament\Admin\Resources;

use App\Models\GatewayUser;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
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

    protected static ?int $navigationSort = 2;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
           TextEntry::make('gateway.name'),
            TextEntry::make('username'),
            Section::make('Certificate Info')->schema([
               TextEntry::make('certificate.common_name')
                   ->label('Common Name'),
               TextEntry::make('certificate.type')
                   ->label('Type'),
               TextEntry::make('certificate.valid_from')
                    ->label('Valid From'),
               TextEntry::make('certificate.valid_to')
                    ->label('Valid To'),
            ])->columns(2),
            Section::make('User Creation Info')->schema([
                TextEntry::make('created_at'),
                TextEntry::make('updated_at_at'),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('certificate')
                    ->boolean()
                    ->state(fn(GatewayUser $gatewayUser) => $gatewayUser->certificate->isExpiring() ?? false)
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->label('Expiring'),
                TextColumn::make('certificate.valid_to')
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
