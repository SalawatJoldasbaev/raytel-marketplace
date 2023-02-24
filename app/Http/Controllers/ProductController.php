<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Services\Product\DestroyProduct;
use Carbon\Carbon;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ViewedProduct;
use App\Services\Product\CreateProduct;
use App\Services\Product\UpdateProduct;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends ApiController
{
    public function index(Request $request): JsonResponse|ProductCollection
    {
        try {
            $stores = Product::orderBy($this->sort, $this->sortDirection)
                ->when($request->get('store_id'), function ($query, $store_id) {
                    return $query->where('store_id', $store_id);
                })
                ->with('store')
                ->paginate($this->getLimitPerPage());
        } catch (QueryException) {
            return $this->respondInvalidQuery();
        }
        return new ProductCollection($stores);
    }

    public function store(Request $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $employee = app(CreateProduct::class)->execute($request->all());
            return ProductResource::collection($employee);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

    }

    public function update(Request $request): JsonResponse|ProductResource
    {
        try {
            $store = app(UpdateProduct::class)->execute([
                'product_id' => $request->product_id,
                'store_id' => $request->store_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'watermark_image' => $request->watermark_image,
            ]);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return new ProductResource($store);
    }

    public function destroy($product): JsonResponse
    {
        try {
            app(DestroyProduct::class)->execute($product);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        }

        return $this->respondObjectDeleted($product);
    }

    public function RandomProduct(Request $request)
    {

        try {
            [$user, $token, $viewedProducts] = $this->check($request);
        } catch (UnauthorizedException){
            return $this->respondUnauthorized();
        } catch (Exception) {
            return $this->limitOver();
        }

        $products = Product::query()->whereNotIn('id', $viewedProducts)->whereHas('store', function ($query) {
            return $query->where('active', true);
        })->inRandomOrder();
        $products = $products->take(100)->get();

        if (empty($products)) {
            $this->setHTTPStatusCode(404);
            return $this->respond([
                'message' => 'Product not found'
            ]);
        }

        if ($token->tokenable_type == 'App\\Models\\User') {
            $active_at = strtotime($user->actived_at);
            $time = Carbon::create(
                date('Y', $active_at),
                date('m', $active_at),
                date('d', $active_at),
                date('H', $active_at),
                date('i', $active_at),
                date('s', $active_at),
            );
            if ($time->addDays(33) <= Carbon::now()) {
                $this->setHTTPStatusCode(403);
                $user->status = 'inactive';
                $user->save();
                return $this->limitOver();
            }
        }
        return ProductResource::collection($products);
    }

    public function viewProduct(Request $request, Product $product): JsonResponse|array
    {
        try {
            [$user, $token, $viewedProducts] = $this->check($request);
        } catch (UnauthorizedException){
            return $this->respondUnauthorized();
        } catch (Exception) {
            return $this->limitOver();
        }

        $data = [
            'product_id' => $product->id,
            'viewed_at' => Carbon::now(),
            'user_id' => $user->id,
        ];
        $alert = false;
        if ($token->tokenable_type == 'App\\Models\\Device') {
            $data['device_id'] = $user->id;
            if (count($viewedProducts) - 15 > 0) {
                for ($i = 15; $i <= 100; $i += 3) {
                    if (count($viewedProducts) + 1 == $i) {
                        $alert = true;
                        break;
                    }
                }
            }

        } elseif ($token->tokenable_type == 'App\\Models\\User') {
            $data['device_id'] = $user->device_id;
            $data['user_id'] = $user->id;
        }

        ViewedProduct::create($data);
        return [
            'alert' => $alert,
            'count' => count($viewedProducts) + 1,
        ];
    }

    /**
     * @throws Exception
     */
    private function check($request): array
    {
        $user = $request->user();
        if ($user->tokenCan('admin')) {
            throw new UnauthorizedException();
        }
        $token = $user->currentAccessToken();
        $viewedProducts = ViewedProduct::whereDate('viewed_at', Carbon::today())->pluck('product_id');
        if (
            $token->tokenable_type == 'App\\Models\\Device' and
            (
                count($viewedProducts) >= $user->limit_left or
                strtotime(date('Y-m-d')) - strtotime($user->created_at) > 259200
            )
        ) {
            throw new Exception('limit');
        }
        return [$user, $token, $viewedProducts];
    }
}
