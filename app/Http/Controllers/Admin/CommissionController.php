<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        return view('Admin.Commission.index');
    }

    public function generate()
    {
        return view('Admin.Commission.generate');
    }
}
