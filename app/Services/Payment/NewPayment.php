<?php

namespace App\Services\Payment;


use App\Exceptions\UnauthorizedException;
use App\Models\Report;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class NewPayment extends BaseService
{
    public function rules():array
    {
        return [
            'image' => 'required|url',
        ];
    }

    /**
     * @throws UnauthorizedException
     * @throws ValidationException
     */
    public function execute(array $data): Report
    {
        $this->validate($data);
        $user = \Auth::user();
        if($user->currentAccessToken()->tokenable_type != 'App\\Models\\User'){
            throw new UnauthorizedException("Aldin sistemag'a login bolip kirin' yaki registrasiyadan otin'");
        }
        return Report::create([
            'name' => $user->name,
            'phone' => $user->phone,
            'image' => $data['image'],
            'status' => 'pending',
            'type' => 'NewPayment',
        ]);
    }
}


