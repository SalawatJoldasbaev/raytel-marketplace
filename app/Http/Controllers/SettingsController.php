<?php

namespace App\Http\Controllers;

use App\Http\Resources\Settings\SettingsResource;
use App\Models\Settings;
use App\Services\Settings\UpdateSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class SettingsController extends ApiController
{
    public function update(Request $request): SettingsResource|JsonResponse
    {
        try {
            $settings = app(UpdateSettings::class)->execute([
                'description'=> $request->get('description'),
                'title'=> $request->get('title'),
                'price'=> $request->get('price'),
                'card_number'=> $request->get('card_number'),
                'card_holder'=> $request->get('card_holder'),
                'end_text'=> $request->get('end_text'),
                'phone'=> $request->get('phone'),
                'block_text'=> $request->get('block_text'),
                'unblock_text'=> $request->get('unblock_text'),
            ]);
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }

        return new SettingsResource($settings);
    }

    public function show(Request $request): SettingsResource
    {
        $settings = Settings::find(1);
        return new SettingsResource($settings);
    }
}
