<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLoginPage;

class Login extends BaseLoginPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->form->fill([
                'email' => 'layer7@admin.com',
                'password' => 'layer7',
                'remember' => true,
            ]);
        }
    }
}
