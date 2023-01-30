<?php

namespace App\Services\Product;

use App\Models\Employee;
use App\Models\Product;
use App\Models\ViewedProduct;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class DestroyProduct extends BaseService
{
    public function rules()
    {
        return [];
    }

    public function execute($product)
    {
        try {
            $product = Product::findOrFail($product);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Employee does not exist');
        }
        ViewedProduct::where('product_id', $product->id)->delete();
        $path = explode(config('app.url') . '/api/', $product->image)[1];
        Storage::delete($path);
        $product->delete();
        return true;
    }
}
