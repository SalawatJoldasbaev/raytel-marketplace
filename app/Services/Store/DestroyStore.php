<?php

namespace App\Services\Store;


use App\Models\Employee;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyStore extends BaseService
{
    public function rules()
    {
        return [];
    }

    public function execute($employee)
    {
        try {
            $employee = Employee::findOrFail($employee);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Employee does not exist');
        }
        $employee->delete();
        return true;
    }
}
