<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Show the about us page.
     */
    public function about(): View
    {
        return view('pages.about');
    }
    public function terms(): View
    {
        return view('pages.terms');
    }
}
