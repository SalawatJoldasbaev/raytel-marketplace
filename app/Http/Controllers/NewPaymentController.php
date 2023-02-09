<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Resources\Report\ReportResource;
use App\Services\Payment\NewPayment;
use Illuminate\Http\Request;

class NewPaymentController extends ApiController
{

    public function newPayment(Request $request){
        try {
            $payment = app(NewPayment::class)->execute($request->all());
        } catch (UnauthorizedException $e) {
            return $this->respondUnauthorized($e->getMessage());
        }
        return new ReportResource($payment);
    }
}
