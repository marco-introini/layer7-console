<?php

namespace App\Filament\Admin\Resources;

use App\Enumerations\CertificateRequestStatus;
use App\Filament\Admin\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Section as InfoListSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('requester')
                    ->schema([
                        Select::make('status')
                            ->enum(CertificateRequestStatus::class)
                            ->options(CertificateRequestStatus::class)
                            ->required(),
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->live()
                            ->required(),
                        Select::make('user_id')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    if ($get('company_id') != '') {
                                        return $query->where('company_id', $get('company_id'));
                                    }

                                    return $query->where('id', 0);
                                }),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoListSection::make('Requester')
                    ->schema([
                        TextEntry::make('status')
                            ->inlineLabel()
                            ->columnSpanFull()
                            ->color(fn (Certificate $certificate): string => $certificate->getFilamentColor())
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),
                        TextEntry::make('user.name'),
                        TextEntry::make('company.name'),
                    ])->columns(),
                InfoListSection::make('Service Requested')
                    ->schema([
                        TextEntry::make('publicService.name'),
                        TextEntry::make('publicService.gatewayService.name')
                            ->label('Mapped to Gateway Service'),
                        TextEntry::make('publicService.gatewayService.gateway.name')
                            ->label('API Gateway'),
                    ])->columns(),
                InfoListSection::make('Issued Certificate')
                    ->schema([
                        TextEntry::make('common_name')
                            ->columnSpanFull(),
                        TextEntry::make('private_key')
                            ->columnSpanFull(),
                        TextEntry::make('public_cert')
                            ->columnSpanFull(),
                        TextEntry::make('valid_from')
                            ->date('Y-m-d H:m:s'),
                        TextEntry::make('valid_to')
                            ->date('Y-m-d H:m:s'),
                    ])->columns()
                    ->visible(fn (Certificate $certificate
                    ) => $certificate->status === CertificateRequestStatus::ISSUED),
                InfoListSection::make('Timestamps')
                    ->schema([
                        TextEntry::make('requested_at')
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
                    ->color(fn (Certificate $certificate): string => $certificate->getFilamentColor()),
                TextColumn::make('user.name')
                    ->label('Requested By')
                    ->description(fn (Certificate $certificate) => $certificate->company->name),
                TextColumn::make('publicService.name')
                    ->description(fn (Certificate $certificate
                    ) => 'Mapped to '.$certificate->publicService->gatewayService?->name.
                        ' Gateway '.$certificate->publicService->gatewayService?->gateway?->name),
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
