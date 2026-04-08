<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CustomerProfileResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nickname' => $this->nickname,
            'avatar_url' => $this->publicFileUrl($this->avatar_url),
            'phone' => $this->phone,
            'balance' => $this->balance,
            'points' => $this->points,
        ];
    }
}
