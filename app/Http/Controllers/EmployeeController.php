<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Resources\EmployeeResource;
use App\Services\Employee\CreateEmployee;
use App\Services\Employee\UpdateEmployee;
use App\Http\Resources\EmployeeCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PhoneAlreadyExistsException;
use App\Services\Employee\DestroyEmployee;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends ApiController
{
    public function createEmployee(Request $request): EmployeeResource|JsonResponse
    {
        try {
            $employee = app(createEmployee::class)->execute([
                'name' => $request->get('name'),
                'password' => $request->get('password'),
                'phone' => $request->get('phone'),
            ]);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return new EmployeeResource($employee);
    }

    public function index(Request $request): JsonResponse|EmployeeCollection
    {
        try {
            $employees = Employee::orderBy($this->sort, $this->sortDirection)
                ->paginate($this->getLimitPerPage());
        } catch (QueryException) {
            return $this->respondInvalidQuery();
        }
        return new EmployeeCollection($employees);
    }

    public function update(Request $request): EmployeeResource|JsonResponse
    {

        try {
            $employee = app(UpdateEmployee::class)->execute([
                'employee_id' => $request->get('employee_id'),
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'password' => $request->get('password'),
            ]);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (PhoneAlreadyExistsException $e) {
            return $this->respondValidatorMessage($e->getMessage());
        }
        return new EmployeeResource($employee);
    }

    public function destroy(Request $request, $employee): Response|JsonResponse
    {
        try {
            app(DestroyEmployee::class)->execute($employee);
        } catch (ModelNotFoundException) {
            return $this->respondNotFound();
        }

        return response(['message' => 'success'], 200);
    }
}
