<?php

namespace App\Http\Controllers\Marketplace\User;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderContoller extends Controller
{
    
    public function list()
    {
        return view('MARKETPLACE.User.list');
    }
}
