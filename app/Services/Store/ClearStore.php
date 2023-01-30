<?php

namespace App\Services\Store;


use App\Models\Employee;
use App\Models\Product;
use App\Models\Store;
use App\Models\ViewedProduct;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ClearStore extends BaseService
{
    public function rules()
    {
        return [];
    }

    public function execute($store)
    {
        try {
            $store = Store::findOrFail($store);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Store does not exist');
        }
        $products = Product::where('store_id', $store->id)->get();
        foreach ($products as $product){
            ViewedProduct::where('product_id', $product->id)->delete();
            $path = explode(config('app.url') . '/api/', $product->image)[1];
            Storage::delete($path);
            $product->delete();
        }
        return true;
    }
}
