<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyEmployee extends BaseService
{
    public function rules():array
    {
        return [];
    }

    /**
     * @param $employee
     * @return bool
     * @throws ModelNotFoundException
     */
    public function execute($employee): bool
    {
        try {
            $employee = Employee::findOrFail($employee);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException('Employee does not exist');
        }
        $employee->delete();
        return true;
    }
}
