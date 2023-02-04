<?php

namespace App\Services\Payment;


use App\Models\Report;
use App\Services\BaseService;

class NewPayment extends BaseService
{
    public function rules()
    {
        return [
            'image' => 'required|url',
        ];
    }

    public function execute(array $data)
    {
        $this->validate($data);
        $user = \Auth::user();
        if($user->currentAccessToken()->tokenable_type != 'App\\Models\\User'){
//            throw
        }
        $report = Report::create([
            'name' => $user->name,
            'phone' => $user->phone,
            'image' => $data['image'],
            'status' => 'pending',
            'type' => 'NewPayment',
        ]);

        return $report;
    }
}


