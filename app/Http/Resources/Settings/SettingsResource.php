<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'description' => $this->description,
            'title' => $this->title,
            'price' => $this->price,
            'card_number' => $this->card_number,
            'card_holder' => $this->card_holder,
            'end_text'=> $this->end_text,
            'phone'=> $this->phone,
            'block_text'=> $this->block_text,
            'unblock_text'=> $this->unblock_text,
            'watermark_text'=> $this->watermark_text,
        ];
    }
}
