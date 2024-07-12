<?php

namespace App\Filament\Admin\Resources\CertificateResource\Pages;

use App\Enumerations\CertificateRequestStatus;
use App\Filament\Admin\Resources\CertificateResource;
use App\Jobs\GenerateX509Job;
use App\Models\Certificate;
use Filament\Actions;
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
                    $record->status = CertificateRequestStatus::APPROVED;
                    GenerateX509Job::dispatch($record, $certificateCN, null);
                    $record->save();

                    Notification::make()
                        ->title('Request Accepted and Certificate Generation Queued')
                        ->success()
                        ->send();
                })
                ->form([
                    TextInput::make('cn')
                        ->label('Please Insert the Common Name for the Certificate')
                        ->minLength(3)
                        ->maxLength(15)
                        ->unique('certificates', 'common_name', ignoreRecord: true)
                        ->required(),
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
        ];
    }

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
