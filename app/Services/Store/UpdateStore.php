<?php

namespace App\Services\Store;

use App\Models\Store;
use App\Models\Employee;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PhoneAlreadyExistsException;

class UpdateStore extends BaseService
{
    public function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'name' => 'required',
            'phone' => 'required',
            'description' => 'nullable',
            'image' => 'nullable',
            'telegram'=> 'nullable',
            'instagram'=> 'nullable',
            'active'=> 'required|boolean'
        ];
    }

    public function execute(array $data): Store
    {
        $this->validate($data);
        $store = Store::findOrFail($data['store_id']);
        $updateData = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'telegram'=> $data['telegram'],
            'instagram'=> $data['instagram'],
            'description' => $data['description'],
            'active'=> $data['active'],
        ];
        if (!is_null($data['image'])) {
            $updateData['image'] = $data['image'];
        }
        $store->update($updateData);
        return $store;
    }
}
