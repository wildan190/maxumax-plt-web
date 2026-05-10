<?php

namespace App\Http\Controllers;

use App\Services\Storefront\CatalogLandingService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request, CatalogLandingService $catalogLandingService)
    {
        return view('home', $catalogLandingService->homeDataset($request));
    }
}
