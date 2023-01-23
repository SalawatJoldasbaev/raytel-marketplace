<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\BaseService;

class UpdateProduct extends BaseService
{
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'store_id' => 'required|exists:stores,id',
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable',
        ];
    }

    public function execute(array $data): Product
    {
        $this->validate($data);
        $product = Product::where('id', $data['product_id'])->where('store_id', $data['store_id'])->firstOrFail();
        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'],
        ];
        if (!is_null($data['image'])) {
            $updateData['image'] = $data['image'];
        }
        $product->update($updateData);
        return $product;
    }
}
