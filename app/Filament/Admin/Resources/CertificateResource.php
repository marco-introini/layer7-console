<?php

namespace App\Filament\Admin\Resources;

use App\Enumerations\CertificateRequestStatus;
use App\Filament\Admin\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Requester')
                    ->schema([
                        TextEntry::make('status')
                            ->inlineLabel()
                            ->columnSpanFull()
                            ->color(fn(Certificate $certificate): string => $certificate->getFilamentColor())
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),
                        TextEntry::make('user.name'),
                        TextEntry::make('company.name'),
                    ])->columns(),
                Section::make('Service Requested')
                    ->schema([

                    ])->columns(),
                Section::make('Timestamps')
                    ->schema([

                    ])->columns()
                    ->visible(fn (Certificate $certificate) => $certificate->status === CertificateRequestStatus::ISSUED),
                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->date('Y-m-d H:m:s'),
                        TextEntry::make('updated_at')
                            ->date('Y-m-d H:m:s'),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(Certificate $certificate): string => $certificate->getFilamentColor()),
                TextColumn::make('user.name')
                    ->label('Requested By')
                    ->description(fn(Certificate $certificate) => $certificate->company->name),
                TextColumn::make('publicService.name')
                    ->description(fn(Certificate $certificate
                    ) => 'Mapped to '.$certificate->publicService->gatewayService?->name),
                TextColumn::make('requested_at')
                    ->label('Request Date'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
            'view' => Pages\ViewCertificate::route('/{record}/view'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
