<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamViewController extends Controller
{
    public function index(){
        return view('Admin.Team.index');
    }
}
