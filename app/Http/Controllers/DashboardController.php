<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function __invoke(Request $request)
    {
        // Optional: Add breadcrumbs if needed
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
        ];

        return view('admin.dashboard', [
            'user' => $request->user(),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
