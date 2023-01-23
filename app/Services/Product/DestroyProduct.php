<?php

namespace App\Services\Product;

use App\Models\Employee;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProduct extends BaseService
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
