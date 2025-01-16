<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('User.Course.index');
    }

    public function myCourses()
    {
        return view('User.Course.my');
    }

    public function view($courseId)
    {
        return view('User.Course.view', compact('courseId'));
    }
}
