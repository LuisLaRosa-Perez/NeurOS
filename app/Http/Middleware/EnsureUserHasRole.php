<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    protected string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->hasRole($this->role)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }

    public static function make(string $role): static
    {
        return new static($role);
    }
}
