<?php

namespace App\Http\Responses\Auth;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;

class RedirectToLoginResponse implements LoginResponseContract
{
    public function __construct(protected string $url)
    {
    }

    public function toResponse($request): RedirectResponse
    {
        return new RedirectResponse($this->url);
    }
}
