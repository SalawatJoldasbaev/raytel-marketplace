<?php

namespace App\Exceptions;

use Exception;

class PhoneAlreadyExistsException extends Exception
{
    public function render($request)
    {
        return response([
            "message" => $this->getMessage()
        ], 422);
    }
}
