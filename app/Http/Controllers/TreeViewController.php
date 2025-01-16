<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreeViewController extends Controller
{
    public function my()
    {
        if (!Auth::user()->is_tree_enabled)
            abort(403);

        return view('User.Tree.my');
    }
}
