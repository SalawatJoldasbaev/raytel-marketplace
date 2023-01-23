<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
