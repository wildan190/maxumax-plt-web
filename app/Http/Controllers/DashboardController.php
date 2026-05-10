<?php

namespace App\Http\Controllers;

use App\Services\Admin\DashboardMetricsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardMetricsService $dashboardMetricsService)
    {
        return view('admin.dashboard', $dashboardMetricsService->buildViewData($request));
    }
}
