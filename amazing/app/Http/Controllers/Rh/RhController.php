<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RhController extends Controller
{
    public function index(string $scope, Request $request)
    {
        return view('modules.rh.index');
    }
}
