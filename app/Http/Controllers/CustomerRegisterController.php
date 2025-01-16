<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerRegisterController extends Controller
{
    public function register()
    {
        $ref = null;
        return view('User/Register/register', compact('ref'));
    }

    public function registerWithRef($ref)
    {
        return view('User/Register/register', compact('ref'));
    }
}
