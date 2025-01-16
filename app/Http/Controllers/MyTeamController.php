<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyTeamController extends Controller
{
    public function my()
    {
        // if (!Auth::user()->is_tree_enabled)
        //     abort(403);

        return view('User.My.team');
    }
}
