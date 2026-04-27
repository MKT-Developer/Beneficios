<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_EDITOR = 'editor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |-------------------------------------------------
    | ROLES BASE
    |-------------------------------------------------
    */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->isSuperAdmin();
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    /*
    |-------------------------------------------------
    | ACCESO AL PANEL
    |-------------------------------------------------
    */
    public function canAccessAdmin(): bool
    {
        return true; // ya lo filtras con middleware
    }

    /*
    |-------------------------------------------------
    | GESTIÓN DE USUARIOS
    |-------------------------------------------------
    */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /*
    |-------------------------------------------------
    | ELIMINACIÓN
    |-------------------------------------------------
    */

    // public function canDeleteUsers(): bool
    // {
    //     return in_array($this->role, [
    //         self::ROLE_SUPERADMIN,
    //         self::ROLE_ADMIN
    //     ]);
    // }

    /*
    |-------------------------------------------------
    | PROTECCIÓN DE CUENTA
    |-------------------------------------------------
    */
    public function isLocked(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN
            && $this->email === 'jesus.castro@meracorporation.com';
    }

    /*
    |-------------------------------------------------
    | USUARIO EDITOR NO PUEDE ELIMINAR
    |-------------------------------------------------
    */
    public function canDelete(): bool
    {
        return $this->isAdmin();
    }

    /*
    |-------------------------------------------------
    | EDITOR NO PUEDE BORRAR NADA
    |-------------------------------------------------
    */
    public function canEdit(): bool
    {
        return $this->isAdmin() || $this->isEditor();
    }
}
