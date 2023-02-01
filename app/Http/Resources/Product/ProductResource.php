<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'watermark_image'=> $this->watermark_image,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'store' => new StoreResource($this->store),
        ];
    }
}
