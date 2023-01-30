<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
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
        ];
    }
}
