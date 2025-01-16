<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // if (!Auth::user()->is_tree_enabled)
        //     abort(403);

        return view('User.Reports.income');
    }
}
