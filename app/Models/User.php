<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'photo',
    'role',
    'can_edit_peminjaman',
    'can_delete_peminjaman',
])]

#[Hidden([
    'password',
    'remember_token'
])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canEditPeminjaman(): bool
    {
        return $this->isSuperAdmin() || (bool) $this->can_edit_peminjaman;
    }

    public function canDeletePeminjaman(): bool
    {
        return $this->isSuperAdmin() || (bool) $this->can_delete_peminjaman;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_edit_peminjaman' => 'boolean',
            'can_delete_peminjaman' => 'boolean',
        ];
    }
}
