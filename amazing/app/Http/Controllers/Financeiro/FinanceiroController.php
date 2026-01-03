<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    public function index(string $scope, Request $request)
    {
        return view('modules.financeiro.index');
    }
}
