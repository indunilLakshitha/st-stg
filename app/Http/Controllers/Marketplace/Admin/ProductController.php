<?php

namespace App\Http\Controllers\Marketplace\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        return view('MARKETPLACE.Admin.Product.Product.index');
    }

    public function create()
    {
        if (Auth::user()->is_admin)
            return view('MARKETPLACE.Admin.Product.Product.create');

        return abort(404);
    }
}
