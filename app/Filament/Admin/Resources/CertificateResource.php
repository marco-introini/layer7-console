<?php

namespace App\Filament\Admin\Resources;

use App\Enumerations\CertificateType;
use App\Filament\Admin\Resources\CertificateResource\Pages\ListCertificates;
use App\Filament\Admin\Resources\CertificateResource\Pages\ViewCertificate;
use App\Models\Certificate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $slug = 'certificates';

    protected static ?string $recordTitleAttribute = 'common_name';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('type')
                    ->required(),

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
                    ->content(fn (?Certificate $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->visibleOn('edit')
                    ->disabled()
                    ->content(fn (?Certificate $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
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
            'index' => ListCertificates::route('/'),
            'view' => ViewCertificate::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
