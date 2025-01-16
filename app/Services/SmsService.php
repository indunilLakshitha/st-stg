<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{


    public function sendOTP(string $mobileNo)
    {

        $formattedMobile =  $this->formatMobileNo(mobileNo: $mobileNo);
        $data = (object) [
            "source" => "Equest.lk",
            "transport" =>  "sms",
            "destination" => $formattedMobile
        ];

        Log::channel('sms')->info('mobile no ' . $formattedMobile);

        $res =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SMS_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(
            'https://api.getshoutout.com/otpservice/send',
            $data
        );

        $res = json_decode($res, TRUE);

        Log::channel('sms')->info('sendOTP : res ' . json_encode($res));

        if (isset($res['referenceId'])) {
            Log::channel('sms')->info('sendOTP : referenceId ' . $res['referenceId']);
            return $res['referenceId'];
        }
        return null;
    }

    public function verifyOTP($otp, $referenceId): bool
    {

        $data = (object) [

            "code" => $otp,
            "referenceId" =>  $referenceId,

        ];

        Log::info(json_encode($data));

        $res =  Http::withHeaders([
            'Authorization' => 'Bearer ' .  env('SMS_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(
            'https://api.getshoutout.com/otpservice/verify',
            $data
        );
        $res = json_decode($res, TRUE);
        Log::channel('sms')->info('verifyOTP : res ' . json_encode($res));
        if (isset($res['statusCode'])) {
            if ($res['statusCode'] == SmsLog::STATUS['OTP_VERIFIED']) {

                Log::channel('sms')->info('verifyOTP : statusCode ' . $res['statusCode']);
                return true;
            }
        }
        return false;
    }

    public function sendMsg($mobileNo, $msg): bool
    {

        $data = (object) [
            "source" => "Equest.lk",
            "destinations" => [$this->formatMobileNo(mobileNo: $mobileNo)],
            "content" =>   (object) [
                "sms" => $msg
            ],
            "transports" => ["sms"]

        ];

        Log::info(json_encode($data));

        $res =  Http::withHeaders([
            'Authorization' => 'Bearer ' .  env('SMS_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(
            'https://api.getshoutout.com/coreservice/messages',
            $data
        );
        $res = json_decode($res, TRUE);
        Log::channel('sms')->info('sendMsg : res ' . json_encode($res));
        if (isset($res['status'])) {
            if ($res['status'] == SmsLog::STATUS['SEND_MSG_SUCCESS']) {

                Log::channel('sms')->info('sendMsg : status ' . $res['status']);
                return true;
            }
        }
        return false;
    }

    private function formatMobileNo(string $mobileNo)
    {
        $number = substr($mobileNo, -9);

        return '94' . $number;
    }
}
