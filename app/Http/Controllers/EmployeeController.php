<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Resources\EmployeeResource;
use App\Services\Employee\CreateEmployee;
use App\Services\Employee\UpdateEmployee;
use App\Http\Resources\EmployeeCollection;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PhoneAlreadyExistsException;
use App\Services\Employee\DestroyEmployee;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends ApiController
{
    public function createEmployee(Request $request)
    {
        try {
            $employee = app(createEmployee::class)->execute([
                'name' => $request->name,
                'password' => $request->password,
                'phone' => $request->phone,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return new EmployeeResource($employee);
    }

    public function index(Request $request)
    {
        try {
            $employees = Employee::orderBy($this->sort, $this->sortDirection)
                ->paginate($this->getLimitPerPage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return new EmployeeCollection($employees);
    }

    public function update(Request $request)
    {

        try {
            $employee = app(UpdateEmployee::class)->execute([
                'employee_id' => $request->employee_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => $request->password,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (PhoneAlreadyExistsException $e) {
            return $this->respondValidatorMessage($e->getMessage());
        }
        return new EmployeeResource($employee);
    }

    public function destroy(Request $request, $employee)
    {
        try {
            app(DestroyEmployee::class)->execute($employee);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return response(['message' => 'success'], 200);
    }
}
