<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use App\Http\Responses\Auth\RedirectToLoginResponse;

class CustomLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            // Find the user's primary role
            $role = $user->getRoleNames()->first();
            if (!$role) {
                throw ValidationException::withMessages([
                    'data.email' => 'No se pudo determinar tu rol de usuario.',
                ]);
            }
            $panelId = strtolower($role);

            // Find the target panel
            try {
                $targetPanel = Filament::getPanel($panelId);

                return new RedirectToLoginResponse($targetPanel->getLoginUrl());
            } catch (\Exception $e) {
                 // Fallback: if a panel isn't found for their role, show an error
                throw ValidationException::withMessages([
                    'data.email' => "No tienes acceso a este panel y no se encontró un panel para tu rol.",
                ]);
            }
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
