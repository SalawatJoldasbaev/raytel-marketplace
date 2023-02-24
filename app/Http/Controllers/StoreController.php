<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreCollection;
use App\Models\Store;
use App\Services\Store\ClearStore;
use Illuminate\Http\Request;
use App\Services\Store\CreateStore;
use App\Http\Resources\StoreResource;
use App\Services\Store\UpdateStore;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class StoreController extends ApiController
{
    public function index(Request $request): JsonResponse|StoreCollection
    {
        $user = $request->user();
        try {
            if ($request->has('store_id')) {
                $per_page = ($this->getLimitPerPage() == 0 ? 15 : $this->getLimitPerPage()) - 1;
                $this->setLimitPerPage($per_page);
            }

            $stores = Store::orderBy($this->sort, $this->sortDirection)
                ->when($request->get('search'), function ($query, $search) {
                    return $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('telegram', 'like', $search . '%')
                        ->orWhere('instagram', 'like', $search . '%');
                })->when($request->get('store_id'), function ($query, $store_id) {
                    $query->whereNot('id', $store_id);
                });

            $token = $user->currentAccessToken();
            if ($token->tokenable_type != 'App\Models\Employee') {
                $stores = $stores->where('active', true);
            }
            $stores = $stores->paginate($this->getLimitPerPage());
            if ($request->has('store_id')) {
                $store = Store::find($request->get('store_id'));
                $count = count($stores);
                $temp = 0;
                for ($i = 0; $i < $count + 1; $i++) {

                    if ($i == 0) {
                        $temp = $stores[0];
                        $stores[0] = $store;
                        continue;
                    }

                    $temp2 = $stores[$i];
                    $stores[$i] = $temp;
                    $temp = $temp2;
                }
            }
        } catch (QueryException) {
            return $this->respondInvalidQuery();
        }
        return new StoreCollection($stores);
    }

    public function show(int $id): JsonResponse|StoreResource
    {
        try {
            return new StoreResource(Store::findOrFail($id));
        }catch (ModelNotFoundException){
            return $this->respondNotFound();
        }
    }

    public function store(Request $request): JsonResponse|StoreResource
    {
        try {
            $employee = app(CreateStore::class)->execute([
                'name' => $request->get('name'),
                'image' => $request->get('image'),
                'description' => $request->get('description'),
                'phone' => $request->get('phone'),
                'telegram' => $request->get('telegram'),
                'instagram' => $request->get('instagram'),
            ]);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return new StoreResource($employee);
    }

    public function update(Request $request): JsonResponse|StoreResource
    {
        try {
            $store = app(UpdateStore::class)->execute([
                'store_id' => $request->get('store_id'),
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'description' => $request->get('description'),
                'image' => $request->get('image'),
                'telegram' => $request->get('telegram'),
                'instagram' => $request->get('instagram'),
                'active' => $request->get('active'),
            ]);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return new StoreResource($store);
    }

    public function clear($store): JsonResponse
    {
        try {
            app(ClearStore::class)->execute($store);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        }
        $this->setHTTPStatusCode(200);
        return $this->respond(['message' => 'success']);
    }
}
