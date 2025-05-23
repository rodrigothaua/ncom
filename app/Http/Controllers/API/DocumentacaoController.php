<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class DocumentacaoController extends Controller
{
    public function index()
    {
        return view('api.documentacao');
    }
}
