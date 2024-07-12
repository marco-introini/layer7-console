<?php

namespace App\Filament\Admin\Resources\CertificateResource\Pages;

use App\Enumerations\CertificateRequestStatus;
use App\Filament\Admin\Resources\CertificateResource;
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
                ->action(function (array $data, Certificate $record) {
                    $certificateCN = $data['cn'];
                    $record->generateX509Data($certificateCN, null); // default expiration for now
                    $record->status = CertificateRequestStatus::ISSUED;
                    $record->save();

                    Notification::make()
                        ->title('Request Accepted and Certificate Generated')
                        ->success()
                        ->send();
                })
                ->form([
                    TextInput::make('cn')
                        ->label('Please Insert the Common Name for the Certificate')
                        ->minLength(3)
                        ->maxLength(15)
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
                ->requiresConfirmation(),
        ];
    }

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
