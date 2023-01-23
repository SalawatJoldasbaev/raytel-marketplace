<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TokenManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthMobileRequest;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\SingInMobilePhoneRequest;
use App\Http\Requests\Auth\SingUpMobilePhoneRequest;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    private TokenManager $tokenManager;
    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function login(AuthRequest $request)
    {
        $user = Employee::where('phone', $request->phone)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'phone or password is incorrect',
            ], 401);
        }

        return response([
            'name' => $user->name,
            'token' => $this->tokenManager->createToken($user, ['admin'])->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $this->tokenManager->destroyTokens($request->user());
        return response([
            'message' => 'success',
        ], 200);
    }

    public function mobileDevice(AuthMobileRequest $request)
    {
        $user = Device::where('device_id', $request->device_id)->first();

        if (!$user) {
            $user = Device::create([
                'device_id' => $request->device_id,
                'limit_left' => 100,
            ]);
        }

        return response([
            'token' => $this->tokenManager->createToken($user, ['mobile'])->plainTextToken,
        ]);
    }

    public function signUpMobile(SingUpMobilePhoneRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            return $this->respondUnauthorized('phone already exists');
        }
        $device = Device::where('device_id', $request->device_id)->first();
        if (!$device) {
            $device = Device::create([
                'device_id' => $request->device_id,
                'limit_left' => 100,
            ]);
        }

        $user = User::create([
            'device_id' => $device->id,
            'phone' => $request->phone,
            'name' => $request->name,
            'status' => 'pending',
            'password' => $request->password,
        ]);

        Report::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'image' => $request->image,
            'status' => 'pending',
        ]);

        return response([
            'token' => $this->tokenManager->createToken($user, ['mobile'])->plainTextToken,
            'status' => 'pending',
        ]);
    }

    public function mobilePhone(SingInMobilePhoneRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user or !Hash::check($request->password, $user->password)) {
            return $this->respondUnauthorized('phone or password is incorrect');
        } elseif ($user->status == 'inactive') {
            return $this->respondUnauthorized('inactive');
        }

        $device = Device::where('device_id', $request->device_id)->first();
        if (!$device) {
            $device = Device::create([
                'device_id' => $request->device_id,
                'limit_left' => null,
            ]);

            $user->update([
                'device_id' => $device->id,
            ]);
        }
        return response([
            'token' => $this->tokenManager->createToken($user, ['mobile'])->plainTextToken,
            'name' => $user->name,
            'status' => $user->status,
        ]);
    }
}
