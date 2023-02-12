<?php

namespace App\Services\Store;


use App\Services\BaseService;
use App\Models\Store;
use Illuminate\Validation\ValidationException;

class CreateStore extends BaseService
{
    public function rules():array
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

    /**
     * @throws ValidationException
     */
    public function execute(array $data): Store
    {
        $this->validate($data);
        $data['active'] = true;
        return Store::create($data);
    }
}
