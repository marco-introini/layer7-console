<?php

namespace App\Filament\Admin\Resources\CertificateResource\Pages;

use App\Enumerations\CertificateRequestStatus;
use App\Enumerations\CertificateValidity;
use App\Filament\Admin\Resources\CertificateResource;
use App\Jobs\GenerateX509Job;
use App\Models\Certificate;
use Filament\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificate extends ViewRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Approve')
                ->visible(fn (Certificate $record) => $record->isApprovable())
                ->modal()
                ->action(function (array $data, Certificate $record) {
                    $certificateCN = $data['cn'];
                    $validity = CertificateValidity::from($data['validity']);
                    $record->status = CertificateRequestStatus::APPROVED;
                    GenerateX509Job::dispatch($record, $certificateCN, $validity->getExpirationDate());
                    $record->save();

                    Notification::make()
                        ->title('Request Accepted and Certificate Generation Queued')
                        ->success()
                        ->send();
                })
                ->form([
                    Split::make([
                        TextInput::make('cn')
                            ->label('Please Insert the Common Name for the Certificate')
                            ->minLength(3)
                            ->maxLength(15)
                            ->unique('certificates', 'common_name', ignoreRecord: true)
                            ->required(),
                        Select::make('validity')
                            ->required()
                            ->options(CertificateValidity::getValues()),
                    ]),
                ]),
            Actions\Action::make('Reject')
                ->visible(fn (Certificate $record) => $record->isApprovable())
                ->action(function (Certificate $record) {
                    $record->status = CertificateRequestStatus::REJECTED;
                    $record->save();
                    Notification::make()
                        ->title('Request Rejected By Admin')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-trash'),
            Actions\Action::make('Regenerate Certificate')
                ->visible(fn (Certificate $record) => $record->certificateCanBeGenerated())
                ->modal()
                ->action(function (array $data, Certificate $record) {
                    $certificateCN = $record->common_name;
                    $validity = CertificateValidity::from($data['validity']);
                    GenerateX509Job::dispatch($record, $certificateCN, $validity->getExpirationDate());
                    $record->save();

                    Notification::make()
                        ->title('Request Accepted and Certificate Regeneration Queued')
                        ->success()
                        ->send();
                })
                ->form([
                    Placeholder::make('warning')
                        ->label('New certificate will replace the existing one!'),
                    Select::make('validity')
                        ->required()
                        ->options(CertificateValidity::getValues()),
                ]),
        ];
    }

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
