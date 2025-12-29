<?php

namespace App\Http\Controllers;

class HubController extends Controller
{
    public function index()
    {
        return view('hub.home');
    }
}
