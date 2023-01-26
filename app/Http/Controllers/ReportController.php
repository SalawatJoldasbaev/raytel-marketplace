<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\Report\ApproveReport;
use Illuminate\Database\QueryException;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;
use App\Services\Report\DeclinedReport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ReportController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $report = Report::orderBy($this->sort, $this->sortDirection)
                ->when($request->status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->paginate($this->getLimitPerPage());
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
        return new ReportCollection($report);
    }

    public function approveReport(Request $request)
    {
        try {
            $report = app(ApproveReport::class)->execute([
                'report_id' => $request->report_id,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return new ReportResource($report);
    }

    public function declinedReport(Request $request)
    {
        try {
            $report = app(DeclinedReport::class)->execute([
                'report_id' => $request->report_id,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        }
        return new ReportResource($report);
    }
}
