<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function render($request)
    {
        return response([
            "message" => $this->getMessage()
        ], 401);
    }
}
