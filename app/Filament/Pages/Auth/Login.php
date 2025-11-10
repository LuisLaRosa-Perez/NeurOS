<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;

class Login extends BaseLogin
{
    protected function getRedirectUrl(): string
    {
        if (Auth::user()->hasRole('admin')) {
            dd('/admin');
            return '/admin';
        }

        if (Auth::user()->hasRole('psicologo')) {
            dd('/psicologo');
            return '/psicologo';
        }

        return parent::getRedirectUrl();
    }
}
