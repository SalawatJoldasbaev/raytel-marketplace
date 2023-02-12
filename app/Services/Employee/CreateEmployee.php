<?php

namespace App\Services\Employee;


use App\Services\BaseService;
use App\Models\Employee;
use Illuminate\Validation\ValidationException;

class CreateEmployee extends BaseService
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:employees,phone',
            'password' => 'required',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function execute(array $data): Employee
    {
        $this->validate($data);
        return Employee::create($data);
    }
}
