<?php

namespace App\Http\Controllers\Marketplace\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('MARKETPLACE.User.cart');
    }
}
