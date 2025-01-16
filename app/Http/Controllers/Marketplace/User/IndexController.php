<?php

namespace App\Http\Controllers\Marketplace\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $cat = $request->cat;
        return view('MARKETPLACE.User.home', compact('cat'));
    }

    public function filter($cat)
    {
        return view('MARKETPLACE.User.home', compact('cat'));
    }
}
