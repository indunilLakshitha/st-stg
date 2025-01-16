<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositedListController extends Controller
{
    public function index()
    {
        return view('Admin.Deposited.index');
    }

    public function pending()
    {
        return view('Admin.Deposited.pending');
    }
}
