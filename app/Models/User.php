<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /** Statuses */
    const PENDING = 'PENDING';
    const ACTIVE = 'ACTIVE';
    const BLOCKED = 'BLOCKED';

    /** Types */
    const ADMINISTRATOR = 'ADMINISTRATOR';
    const CUSTOMER = 'CUSTOMER';

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
        'token_expired_at',
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

    /**
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
