<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseCategoryController extends Controller
{
    public function index()
    {
        return view('Admin.Course.Category.index');
    }

    public function create()
    {
        if (Auth::user()->is_admin)
            return view('Admin.Course.Category.create');

        return abort(404);
    }

    public function edit($id)
    {
        if (Auth::user()->is_admin)
            return view('Admin.Course.Category.edit', compact('id'));

        return abort(404);
    }
}
