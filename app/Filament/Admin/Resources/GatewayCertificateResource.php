<?php

namespace App\Filament\Admin\Resources;

use App\Enumerations\CertificateType;
use App\Filament\Admin\Resources\GatewayCertificateResource\Pages\ListGatewayCertificates;
use App\Filament\Admin\Resources\GatewayCertificateResource\Pages\ViewGatewayCertificate;
use App\Models\GatewayCertificate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GatewayCertificateResource extends Resource
{
    protected static ?string $model = GatewayCertificate::class;

    protected static ?string $slug = 'certificates';

    protected static ?string $recordTitleAttribute = 'common_name';

    protected static ?string $navigationGroup = 'Gateway Administration';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('type')
                    ->required(),

                Select::make('gateway_id')
                    ->required()
                    ->relationship('gateway', 'name'),

                TextInput::make('common_name')
                    ->required(),

                DatePicker::make('valid_from')
                    ->format('Yd/m/y H:i'),

                DatePicker::make('valid_to')
                    ->format('Yd/m/y H:i'),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->visibleOn('edit')
                    ->disabled()
                    ->content(fn (?GatewayCertificate $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->visibleOn('edit')
                    ->disabled()
                    ->content(fn (?GatewayCertificate $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('common_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('valid_from')
                    ->sortable()
                    ->date(),

                TextColumn::make('valid_to')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(CertificateType::associativeForFilamentFilter()),
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGatewayCertificates::route('/'),
            'view' => ViewGatewayCertificate::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
