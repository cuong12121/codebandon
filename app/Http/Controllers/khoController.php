<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class khoController extends Controller
{
    public function index()
    {
        return view('admin.kho');
    }
}
