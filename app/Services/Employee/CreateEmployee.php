<?php

namespace App\Services\Employee;


use App\Services\BaseService;
use App\Models\Employee;

class CreateEmployee extends BaseService
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:employees,phone',
            'password' => 'required',
        ];
    }

    public function execute(array $data): Employee
    {
        $this->validate($data);
        $employee = Employee::create($data);
        return $employee;
    }
}
