<?php

namespace App\Http\Controllers;

use App\Services\Admin\AdminSalesReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request, AdminSalesReportService $salesReportService)
    {
        $data = $salesReportService->indexDataset($request);

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Sales Report', 'url' => route('admin.reports.index')]
        ));

        return view('admin.reports.index', $data);
    }

    public function export(Request $request, AdminSalesReportService $salesReportService): StreamedResponse
    {
        return $salesReportService->exportCsv($request);
    }
}
