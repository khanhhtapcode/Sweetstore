<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Role constants
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * Get all available roles
     */
    public static function getRoles()
    {
        return [
            self::ROLE_USER => 'Khách hàng',
            self::ROLE_ADMIN => 'Quản trị viên',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Get role name in Vietnamese
     */
    public function getRoleNameAttribute()
    {
        $roles = self::getRoles();
        return $roles[$this->role] ?? 'Không xác định';
    }

    /**
     * Scope to get only admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope to get only users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', self::ROLE_USER);
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationship: User orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Update last login time
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
