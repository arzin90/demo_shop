<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

trait CustomTimestampsFormat
{
    /**
     * Formats "created_at" as 'Y-m-d H:i:s'.
     *
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::make($value)->format('Y-m-d H:i:s'),
        );
    }

    /**
     * Formats "updated_at" as 'Y-m-d H:i:s'.
     *
     * @return Attribute
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::make($value)->format('Y-m-d H:i:s'),
        );
    }
}
