<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\TokenManager;
use App\Http\Requests\Auth\AuthMobileRequest;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\SingInMobilePhoneRequest;
use App\Http\Requests\Auth\SingUpMobilePhoneRequest;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    private TokenManager $tokenManager;

    /**
     * @param TokenManager $tokenManager
     */
    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }
    public function login(AuthRequest $request): Response
    {
        $user = Employee::where('phone', $request->get('phone'))->first();
        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response([
                'message' => 'phone or password is incorrect',
            ], 401);
        }

        return response([
            'name' => $user->name,
            'token' => $this->tokenManager->createToken($user, ['admin'])->plainTextToken,
        ]);
    }

    public function logout(Request $request): Response
    {
        $this->tokenManager->destroyTokens($request->user());
        return response([
            'message' => 'success',
        ], 200);
    }

    public function mobileDevice(AuthMobileRequest $request): Response
    {
        $user = Device::where('device_id', $request->get('device_id'))->first();

        if (!$user) {
            $user = Device::create([
                'device_id' => $request->get('device_id'),
                'limit_left' => 100,
            ]);
        }

        return response([
            'token' => $this->tokenManager->createToken($user, ['mobile'])->plainTextToken,
        ]);
    }

    public function signUpMobile(SingUpMobilePhoneRequest $request): JsonResponse|Response
    {
        $user = User::where('phone', $request->get('phone'))->first();
        if ($user) {
            return $this->respondUnauthorized('phone already exists');
        }
        $device = Device::where('device_id', $request->get('device_id'))->first();
        if (!$device) {
            $device = Device::create([
                'device_id' => $request->get('device_id'),
                'limit_left' => 100,
            ]);
        }

        $user = User::create([
            'device_id' => $device->id,
            'phone' => $request->get('phone'),
            'name' => $request->get('name'),
            'status' => 'pending',
            'password' => $request->get('password'),
        ]);

        Report::create([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'image' => $request->get('image'),
            'status' => 'pending',
            'type'=> 'singUp',
        ]);

        return response([
            'token' => $this->tokenManager->createToken($user, ['mobile'])->plainTextToken,
            'status' => 'pending',
        ]);
    }

    public function mobilePhone(SingInMobilePhoneRequest $request): Response|JsonResponse
    {
        $user = User::where('phone', $request->get('phone'))->first();
        if (!$user or !Hash::check($request->get('password'), $user->password)) {
            return $this->respondUnauthorized('phone or password is incorrect');
        }

        $device = Device::where('device_id', $request->get('device_id'))->first();
        if (!$device) {
            $device = Device::create([
                'device_id' => $request->get('device_id'),
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
