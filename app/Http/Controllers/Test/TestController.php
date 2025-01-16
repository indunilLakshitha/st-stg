<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Mail\SendRegistrationSuccesEmail;
use App\Models\MailDetail;
use App\Models\User;
use App\Services\SmsService;
use App\Traits\ComissionTrait;
use App\Traits\SMSTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    use ComissionTrait, SMSTrait;

    public function testPointAdding()
    {
        //     $user = User::find(23);
        //    return (new PointsToReferralUsecase())->handle(user: $user, point: 25);


        $details['type'] = MailDetail::MAIL_TYPE['REG_SUCCESS'];
        // $details['type'] = MailDetail::MAIL_TYPE['ADMIN_APPROVED'];
        $details['email'] = 'skgindunil@gmail.com';
        $details['password'] = 'REG-SUCCESS';
        $details['user_id'] = 'ID';

        $details['first_name'] = 'first_name';
        $details['course_name'] = 'course_name';
        $details['fee'] = 'fee';


        dispatch(new SendMailJob($details));
    }

    public function correctApprovedAt()
    {
        $users = User::where('approved_by_admin', 1)->get();
        foreach ($users as $user) {
            User::where('parent_id', $user->id)->update(['approved_at' => $user->approved_at]);
        }

        return "done";
    }

    public function sendOTP()
    {
        return (new SmsService())->sendOTP(mobileNo: '0761262279');
    }

    public function sendMSG()
    {
        $details['first_name'] = 'first_name';
        $details['course_name'] = 'course_name';
        $details['fee'] = 'fee';

        (new SmsService())->sendMSG(mobileNo: '0761262279', msg: $this->getRegisteredSuccessSms(
            name: $details['first_name'],
            course_name: $details['first_name'],
            amount: $details['first_name'],
        ));

        (new SmsService())->sendMSG(mobileNo: '0761262279', msg: $this->getApprovedSms(
            name: $details['first_name']
        ));
    }

    public function verifyOTP($otp, $referenceId)
    {

        $data = (object) [

            "code" => $otp,
            "referenceId" =>  $referenceId,

        ];
        // Log::channel('sms')->info($this->getSmsUrl());
        Log::info(json_encode($data));

        $res =  Http::withHeaders([
            'Authorization' => 'Bearer ' . 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzM2U4ZDc5MC1lMmNlLTExZWItOTJmYi1jMTQwMTM4MDhjYWMiLCJzdWIiOiJTSE9VVE9VVF9BUElfVVNFUiIsImlhdCI6MTYyNjA2NjA4MiwiZXhwIjoxOTQxNTk4ODgyLCJzY29wZXMiOnsiYWN0aXZpdGllcyI6WyJyZWFkIiwid3JpdGUiXSwibWVzc2FnZXMiOlsicmVhZCIsIndyaXRlIl0sImNvbnRhY3RzIjpbInJlYWQiLCJ3cml0ZSJdfSwic29fdXNlcl9pZCI6IjExNDc3Iiwic29fdXNlcl9yb2xlIjoidXNlciIsInNvX3Byb2ZpbGUiOiJhbGwiLCJzb191c2VyX25hbWUiOiIiLCJzb19hcGlrZXkiOiJub25lIn0.1Oo1-XN06xZkfM8ajr6wAGZnvO-SwpjLjTz9vmM9MYw',
            'Content-Type' => 'application/json',
        ])->post(
            'https://api.getshoutout.com/otpservice/verify',
            $data
        );
        $res = json_decode($res, TRUE);

        return $res;
    }

    public function transferDummyCommission()
    {
        return $this->transferDummyCommissions();
    }
}
