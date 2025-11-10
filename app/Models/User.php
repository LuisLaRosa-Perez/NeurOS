<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_id',
        'psicologo_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación Padre-Hijo (un hijo tiene un padre)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relación Padre-Hijo (un padre tiene muchos hijos)
    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // Alias en español para la relación children
    public function hijos(): HasMany
    {
        return $this->children();
    }

    // Tareas asignadas a un niño
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'child_id');
    }

    // Relación Padre-Psicólogo (un padre/usuario tiene un psicólogo)
    public function psicologo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'psicologo_id');
    }

    // Relación Psicólogo-Padre (un psicólogo tiene muchos padres/usuarios)
    public function padres(): HasMany
    {
        return $this->hasMany(User::class, 'psicologo_id');
    }
}
