<?php

namespace App\Api\V1\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email_verified_at' => $this->email_verified_at,
            'email' => $this->email,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
