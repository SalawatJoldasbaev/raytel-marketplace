<?php

namespace App\Services\Report;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class ApproveReport extends BaseService
{
    public function rules():array
    {
        return [
            'report_id' => 'required',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function execute(array $data): Report
    {
        $this->validate($data);
        $report = Report::findOrFail($data['report_id']);
        $user = User::where('phone', $report->phone)->firstOrFail();
        $user->actived_at = Carbon::now();
        $user->status = 'active';
        $report->status = 'accept';
        $report->save();
        $user->save();
        return $report;
    }
}
