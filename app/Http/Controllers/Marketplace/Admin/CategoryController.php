<?php

namespace App\Http\Controllers\Marketplace\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('MARKETPLACE.Admin.Product.Category.index');
    }

    public function create()
    {
        // if (Auth::user()->is_admin)
            return view('MARKETPLACE.Admin.Product.Category.create');

        return abort(404);
    }

    public function edit($id)
    {
        if (Auth::user()->is_admin)
            return view('Admin.Course.Category.edit', compact('id'));

        return abort(404);
    }
}
