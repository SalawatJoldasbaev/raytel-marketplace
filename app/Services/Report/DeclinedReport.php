<?php

namespace App\Services\Report;


use App\Models\User;
use App\Models\Report;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class DeclinedReport extends BaseService
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
        $user = User::where('phone', $report->phone)->first();
        $user?->delete();
        $report->update([
            'status' => 'not accepted'
        ]);
        return $report;
    }
}
