<?php

namespace App\Models;

use App\Traits\CustomTimestampsFormat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, CustomTimestampsFormat;

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
        'token_expired_at',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'custom_datetime:Y-m-d H:i:s',
            'token_expired_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeCustomers(Builder $builder): Builder
    {
        return $builder->where('type', self::CUSTOMER);
    }

    /**
     * @param Builder $builder
     * @param string|null $search
     * @return Builder
     */
    public function scopeSearch(Builder $builder, ?string $search): Builder
    {
        if ($search) {
            return $builder->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array|null $status
     * @return Builder
     */
    public function scopeFilterStatus(Builder $builder, ?array $status): Builder
    {
        if ($status) {
            return $builder->whereIn('status', $status);
        }

        return $builder;
    }
}
