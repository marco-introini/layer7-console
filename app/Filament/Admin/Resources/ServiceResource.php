<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceResource\Pages\ListServices;
use App\Filament\Admin\Resources\ServiceResource\Pages\ViewService;
use App\Models\Service;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?int $navigationSort = 4;

    protected static ?string $pluralLabel = 'Services';

    protected static ?string $label = 'Service';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('gateway_id')
                    ->required()
                    ->relationship('gateway', 'name'),
                TextInput::make('name'),
                TextInput::make('url')
                    ->label('Exposed Endpoint'),
                TextInput::make('gateway_user_id'),
                KeyValue::make('backends')
                    ->keyLabel('Type')
                    ->valueLabel('Url')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('Exposed Endpoint')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make('export')
                    ->label('Excel Export')
                    ->exports([
                        ExcelExport::make()
                            ->withFilename(date('Y-m-d').'-Services')
                            ->withWriterType(Excel::XLSX)
                            ->withColumns([
                                Column::make('name')
                                    ->heading('Name'),
                                Column::make('url')
                                    ->heading('Exposed Url'),
                                Column::make('backends')
                                    ->heading('Backends'),
                            ]),
                    ]),
            ])
            ->actions([
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
            'index' => ListServices::route('/'),
            'view' => ViewService::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
