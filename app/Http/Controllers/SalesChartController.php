<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesChartController extends Controller
{

    public function sales()
    {
        // if (!Auth::user()->is_tree_enabled)
        //     abort(403);

        return view('User.My.sales');
    }

    public function pendingActivations()
    {
        return view('User.My.pending-activations');
    }
}
