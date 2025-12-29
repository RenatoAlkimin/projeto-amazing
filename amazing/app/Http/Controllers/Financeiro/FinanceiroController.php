<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;

class FinanceiroController extends Controller
{
    public function index()
    {
        return view('financeiro.home');
    }
}
