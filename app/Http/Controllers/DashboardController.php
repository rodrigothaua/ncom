<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //Implementando o método index no controlador
    public function index()
    {
        return view('dashboard');
    }
}
