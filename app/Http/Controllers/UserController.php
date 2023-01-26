<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserCollection;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UserController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $users = User::orderBy($this->sort, $this->sortDirection)
                ->when($request->search, function ($query, $search) {
                    return $query->where('phone', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                })->when($request->status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->paginate($this->getLimitPerPage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return new UserCollection($users);
    }
}
