<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get avatar URL or a default one.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar && file_exists(public_path('avatars/' . $this->avatar))) {
            return asset('avatars/' . $this->avatar);
        }

        // Default: generate initials-based avatar via UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
            . '&background=4f46e5&color=fff&size=128&bold=true';
    }
}
