<?php

namespace App\Traits;

use App\Models\User;

trait SMSTrait
{
    public function getRegisteredSuccessSms($name, $course_name, $amount)
    {
        return 'Hi ' . $name . ',' . '\nYour registration at Equest Institute for ' . $course_name . ' is successful!' .
            '\nPlease deposit ' . $amount . 'to Sampath Bank (Acc: ' . env('ACC_NUMBER') . ', ' . env('ACC_BRANCH') . ') to confirm.
        \nThank you!';
    }

    public function getApprovedSms($name)
    {
        return 'Hi ' . $name . '\nYour Accout has been approved';
    }

    public function getWithdrawedSms($amount)
    {
        return 'Hi ' . '\nThe withdrawal of ' . $amount . ' has been completed, and the funds have been successfully deposited. Thank you!';
    }
}
