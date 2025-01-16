<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursePaymentController extends Controller
{
    /**
     * @param $paymentMethod = ONLINE | BANK = 2 | WALLET = 1
     * @param $paymentType = FULL = 2 | HALF = 1
     */
    public function pay($courseId, $paymentMethod, $paymentType)
    {
        $course = Course::where('id', $courseId)->first();
        if (!isset($course))
            abort(404);

        if ($paymentType == 1) {
            $payment = $course->installment_1;
        }
        if ($paymentType == 2) {
            $payment = $course->course_price;
        }
        $courseId = $course->id;



        if ($paymentMethod == 1) {
            return view('User.Course.Pay.wallet', compact('courseId', 'payment','paymentType'));
        }

        if ($paymentMethod == 2) {
            return view('User.Course.Pay.direct', compact('courseId', 'payment','paymentType'));
        }
    }
}
