<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('mobile.dashboard');
    }
}