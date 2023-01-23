<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreCollection;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Services\Store\CreateStore;
use App\Http\Resources\StoreResource;
use App\Services\Store\UpdateStore;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoreController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $stores = Store::orderBy($this->sort, $this->sortDirection)
                ->paginate($this->getLimitPerPage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return new StoreCollection($stores);
    }

    public function store(Request $request)
    {
        try {
            $employee = app(CreateStore::class)->execute([
                'name' => $request->name,
                'image' => $request->image,
                'description' => $request->description,
                'phone' => $request->phone,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return new StoreResource($employee);
    }

    public function update(Request $request)
    {
        try {
            $store = app(UpdateStore::class)->execute([
                'store_id' => $request->store_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'description' => $request->description,
                'image' => $request->image,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return new StoreResource($store);
    }

    public function destroy($store)
    {
    }
}
