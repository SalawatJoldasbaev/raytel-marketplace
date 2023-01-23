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
        ];
    }

    public function execute(array $data): Store
    {
        $this->validate($data);
        $store = Store::create($data);
        return $store;
    }
}
