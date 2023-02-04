<?php

namespace App\Http\Controllers;

use App\Http\Resources\Report\ReportResource;
use App\Services\Payment\NewPayment;
use Illuminate\Http\Request;

class NewPaymentController extends Controller
{
    public function newPayment(Request $request){
        $payment = app(NewPayment::class)->execute($request->all());
        return new ReportResource($payment);
    }
}
