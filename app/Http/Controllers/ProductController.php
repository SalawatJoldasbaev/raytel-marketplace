<?php

namespace App\Http\Controllers;

use App\Services\Product\DestroyProduct;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ViewedProduct;
use App\Services\Product\CreateProduct;
use App\Services\Product\UpdateProduct;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\RandomProductResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse|ProductCollection
    {
        try {
            $stores = Product::orderBy($this->sort, $this->sortDirection)
                ->when($request->store_id, function ($query, $store_id) {
                    return $query->where('store_id', $store_id);
                })
                ->with('store')
                ->paginate($this->getLimitPerPage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return new ProductCollection($stores);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse|ProductResource
    {
        try {
            $employee = app(CreateProduct::class)->execute([
                'store_id' => $request->store_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'watermark_image'=> $request->watermark_image,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return new ProductResource($employee);
    }

    public function update(Request $request)
    {
        try {
            $store = app(UpdateProduct::class)->execute([
                'product_id' => $request->product_id,
                'store_id' => $request->store_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'watermark_image'=> $request->watermark_image,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return new ProductResource($store);
    }

    public function destroy($product)
    {
        try {
            app(DestroyProduct::class)->execute($product);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return response(['message' => 'success'], 200);
    }

    public function RandomProduct(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();
        if ($token->tokenable_type == 'App\\Models\\User') {
            $actived_at = strtotime($user->actived_at);
            $time = Carbon::create(
                date('Y', $actived_at),
                date('m', $actived_at),
                date('d', $actived_at),
                date('H', $actived_at),
                date('i', $actived_at),
                date('s', $actived_at),
            );
            if ($time->addDays(33) <= Carbon::now()) {
                $this->setHTTPStatusCode(403);
                $user->status = 'inactive';
                $user->save();
                return $this->respond([
                    'error' => [
                        'message' => 'limit is over'
                    ],
                    'error_code' => 43,
                ]);
            }
        }

        if ($user->tokenCan('mobile')) {
            $viewdProducts = ViewedProduct::whereDate('viewed_at', Carbon::today())->pluck('product_id');
            $product = Product::whereNotIn('id', $viewdProducts)->whereHas('store', function ($query){
                return $query->where('active', true);
            })->inRandomOrder()->first();

            if (!$product) {
                $this->setHTTPStatusCode(404);
                return $this->respond([
                    'message' => 'Product not found'
                ]);
            }

            if (
                $token->tokenable_type == 'App\\Models\\Device' and
                (count($viewdProducts) == $user->limit_left or
                    strtotime(date('Y-m-d')) - strtotime($user->created_at) > 259200
                )
            ) {
                $this->setHTTPStatusCode(403);
                return $this->respond([
                    'error' => [
                        'message' => 'limit is over'
                    ],
                    'error_code' => 43,
                ]);
            }

            $data = [
                'product_id' => $product->id,
                'viewed_at' => Carbon::now(),
                'user_id' => $user->id,
            ];

            if ($token->tokenable_type == 'App\\Models\\Device') {
                $data['device_id'] = $user->id;
            } elseif ($token->tokenable_type == 'App\\Models\\User') {
                $actived_at = strtotime($user->actived_at);
                $time = Carbon::create(
                    date('Y', $actived_at),
                    date('m', $actived_at),
                    date('d', $actived_at),
                    date('H', $actived_at),
                    date('i', $actived_at),
                    date('s', $actived_at),
                );
                if ($time->addDays(33) <= Carbon::now()) {
                    $this->setHTTPStatusCode(403);
                    $user->status = 'inactive';
                    $user->save();
                    return $this->respond([
                        'error' => [
                            'message' => 'limit is over'
                        ],
                        'error_code' => 43,
                    ]);
                }
                $data['device_id'] = $user->device_id;
                $data['user_id'] = $user->id;
            }

            ViewedProduct::create($data);
            return (new RandomProductResource($product))->view_count(count($viewdProducts) + 1);
        }
    }
}
