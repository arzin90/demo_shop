<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** Statuses */
    const PENDING = 'PENDING';
    const ACTIVE = 'ACTIVE';
    const BLOCKED = 'BLOCKED';

    /** Types */
    const ADMINISTRATOR = 'ADMINISTRATOR';
    const CUSTOMER = 'CUSTOMER';

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'status',
        'type',
        'email',
        'password',
        'token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'token',
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
            'token_expired_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @var array|string[]
     */
    public static array $statuses = [
        self::PENDING,
        self::ACTIVE,
        self::BLOCKED,
    ];

    /**
     * @var array|string[]
     */
    public static array $types = [
        self::ADMINISTRATOR,
        self::CUSTOMER,
    ];
}
