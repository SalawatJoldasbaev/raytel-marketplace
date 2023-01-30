<?php

namespace App\Services\Settings;

use App\Models\Settings;
use App\Services\BaseService;

class UpdateSettings extends BaseService
{
    /**
     * @return string[]
     */
    public function rules()
    {
        return [
            'description'=> 'required',
            'title'=> 'required',
            'price'=> 'required|numeric',
            'card_number'=> 'required',
            'card_holder'=> 'required',
            'block_text'=> 'required',
            'unblock_text'=> 'required',
            'end_text'=> 'required',
            'phone'=> 'required',
        ];
    }

    /**
     * @param array $data
     * @return Settings
     */
    public function execute(array $data): Settings
    {
        $this->validate($data);
        $settings = Settings::find(1);
        $settings->update($data);
        return $settings;
    }
}
