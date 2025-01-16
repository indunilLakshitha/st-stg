<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function history()
    {
        return view('User.Order.history');
    }

    public function marketHistoristory()
    {
        return view('User.Order.market-history');
    }
}
