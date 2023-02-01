<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RandomProductResource extends JsonResource
{
    protected $view_count;

    public function view_count($value)
    {
        $this->view_count = $value;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'watermark_image'=> $this->watermark_image,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'view_count' => $this->view_count,
            'store' => new StoreResource($this->store),
        ];
    }
}
