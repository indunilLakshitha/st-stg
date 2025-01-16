<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingControlelr extends Controller
{
    public function settings()
    {
        return view('Admin.Settings.index');
    }
}
