<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PhoneAlreadyExistsException;

class UpdateEmployee extends BaseService
{
    public function rules():array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'name' => 'required',
            'phone' => 'required',
            'password' => 'required',
        ];
    }

    /**
     * @throws PhoneAlreadyExistsException
     * @throws ValidationException
     */
    public function execute(array $data): Employee
    {
        $this->validate($data);
        $check = Employee::where('phone', $data['phone'])->first();
        $employee = Employee::findOrFail($data['employee_id']);
        if (isset($check) and $check->id != $employee->id) {
            throw new PhoneAlreadyExistsException('phone already exists');
        }
        $employee->update($data);
        return $employee;
    }
}
