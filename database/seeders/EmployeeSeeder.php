<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Employee::create([
            'name' => 'Salawat',
            'phone' => '+998907091931',
            'password' => 'pass',
        ]);
    }
}
