<?php

namespace App\Http\Controllers\Marketplace\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function view($slug)
    {
        return view('MARKETPLACE.User.item', compact('slug'));
    }
}
