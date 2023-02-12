<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Http\Resources\Report\ReportResource;
use App\Services\Payment\NewPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewPaymentController extends ApiController
{

    public function newPayment(Request $request): ReportResource|JsonResponse
    {
        try {
            $payment = app(NewPayment::class)->execute($request->all());
            return new ReportResource($payment);
        } catch (UnauthorizedException $e) {
            return $this->respondUnauthorized($e->getMessage());
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
    }
}
