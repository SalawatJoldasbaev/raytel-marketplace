<?php

namespace App\Services\Store;


use App\Models\Product;
use App\Models\Store;
use App\Models\ViewedProduct;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ClearStore extends BaseService
{
    public function rules():array
    {
        return [];
    }

    public function execute($store): bool
    {
        try {
            $store = Store::findOrFail($store);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException('Store does not exist');
        }
        $products = Product::where('store_id', $store->id)->get();
        foreach ($products as $product){
            ViewedProduct::where('product_id', $product->id)->delete();
            $path = explode(config('app.url') . '/api/', $product->image);
            if(key_exists(1, $path)){
                Storage::delete($path[1]);
            }
            $product->delete();
        }
        return true;
    }
}
