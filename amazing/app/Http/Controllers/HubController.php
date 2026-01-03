<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HubController extends Controller
{
    public function index(string $scope, Request $request)
    {
        return view('modules.hub.index');
    }
}
