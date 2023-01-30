<?php

namespace App\Services\Store;


use App\Services\BaseService;
use App\Models\Store;

class CreateStore extends BaseService
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'description' => 'nullable',
            'image' => 'required',
            'telegram'=> 'nullable',
            'instagram'=> 'nullable',
        ];
    }

    public function execute(array $data): Store
    {
        $data['active'] = true;
        $this->validate($data);
        return Store::create($data);
    }
}
