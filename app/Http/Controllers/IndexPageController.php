<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexPageController extends Controller
{
    public function index()
    {

        return view('home.index');
    }

    public function courses()
    {
        $ref = null;
        return view('home.courses', compact('ref'));
    }

    public function coursesWithReferrel($ref)
    {

        return view('home.courses', compact('ref'));
    }

    public function registerWithRef($ref, $course)
    {
        return view('home.register', compact('ref', 'course'));
    }

    public function register()
    {
        $ref = null;
        $course = null;
        return view('home.register', compact('ref', 'course'));
    }

    public function regWithcourse($course)
    {
        $ref = null;
        return view('home.register', compact('ref', 'course'));
    }

    public function checkout($user_id)
    {

        return view('home.checkout', compact('user_id'));
    }

    public function contactUs()
    {
        return view('home.contact-us');
    }

    public function ourTeam()
    {
        return view('home.our-team');
    }

    public function aboutUs()
    {
        return view('home.about-us');
    }

    public function thankYou($user_id)
    {
        return view('home.thank-you', compact('user_id'));
    }

    public function termsNConditn()
    {
        return view('home.termsNc');
    }

    public function pp()
    {
        return view('home.privacy-policy');
    }
}
