<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TokenHelper
{
    /**
     * @param int $length
     * @return array
     */
    public static function getToken(int $length = 100): array
    {
        return [
            'token' => Str::random($length),
            'token_expired_at' => Carbon::now()->addHour(),
        ];
    }
}
