<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;

class ComercialController extends Controller
{
    public function index()
    {
        return view('comercial.home');
    }
}
