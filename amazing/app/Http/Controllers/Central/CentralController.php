<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;

class CentralController extends Controller
{
    public function index()
    {
        return view('central.home');
    }
}
