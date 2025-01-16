<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        return view('Admin.Customer.index');
    }

    public function create()
    {
        if (Auth::user()->is_admin)
            return view('Admin.Profile.create');

        return abort(404);
        // return view('Admin.User.create');
    }

    public function edit($id)
    {
        return view('Admin.Profile.edit', compact('id'));
        // return view('Admin.User.edit', compact('id'));
    }

    public function requests()
    {
        return view('Admin.Customer.requests');
    }

    public function registrationRequests()
    {
        return view('Admin.Customer.registration-requests');
    }
}
