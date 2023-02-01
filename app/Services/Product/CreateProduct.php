<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\BaseService;
use App\Models\Store;

class CreateProduct extends BaseService
{
    public function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'required',
            'watermark_image'=> 'required',
        ];
    }

    public function execute(array $data): Product
    {
        $this->validate($data);
        return Product::create($data);
    }
}
