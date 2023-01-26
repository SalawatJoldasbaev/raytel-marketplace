<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'status' => $this->status,
            'actived_at' => $this->actived_at,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'deadline' => $this->actived_at == null ? null : date('Y-m-d H:i:s', strtotime($this->actived_at) + 2851200),
        ];
    }
}
