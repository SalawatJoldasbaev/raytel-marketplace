<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'url' => $this->url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
