<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MosaicoController extends Controller
{
    public function index()
    {
        return view('mosaico/editor');
    }
}
