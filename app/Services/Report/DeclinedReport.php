<?php

namespace App\Services\Report;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use App\Services\BaseService;

class DeclinedReport extends BaseService
{
    public function rules()
    {
        return [
            'report_id' => 'required',
        ];
    }

    public function execute(array $data): Report
    {
        $this->validate($data);
        $report = Report::findOrFail($data['report_id']);
        $user = User::where('phone', $report->phone)->first();
        if ($user) {
            $user->delete();
        }
        $report->update([
            'status' => 'not accepted'
        ]);
        return $report;
    }
}
