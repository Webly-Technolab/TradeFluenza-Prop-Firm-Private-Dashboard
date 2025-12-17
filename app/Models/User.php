<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'propfirm_name',
        'propfirm_email',
        'propfirm_mobile',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get payouts for this propfirm user.
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'propfirm_id');
    }

    /**
     * Scope for admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for propfirm users.
     */
    public function scopePropfirm($query)
    {
        return $query->where('role', 'propfirm');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is propfirm.
     */
    public function isPropfirm(): bool
    {
        return $this->role === 'propfirm';
    }
}
