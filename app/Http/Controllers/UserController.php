<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function activeUser(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return $this->respondNotFound();
        }
        $user->actived_at = Carbon::now();
        $user->save();
        return;
    }
}
