<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function gallery()
    {
        $galleries = Gallery::latest()->paginate(20);
        return view('gallery.index', compact('galleries'));
    }
}
