<?php

namespace App\Services\Product;

use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\BaseService;
use App\Models\Store;
use Illuminate\Validation\ValidationException;

class CreateProduct extends BaseService
{
    public function rules():array
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'products'=> 'required|array',
            'products.*.name' => 'required',
            'products.*.description' => 'nullable',
            'products.*.image' => 'required',
            'products.*.watermark_image'=> 'required',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function execute(array $data): array
    {
        $this->validate($data);
        $final = [];
        foreach ($data['products'] as $item) {
            $temp = $item;
            $temp['store_id'] = $data['store_id'];
            $final[] = Product::create($temp);
        }
        return $final;
    }
}
