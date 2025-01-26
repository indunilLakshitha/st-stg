<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Mail\SendRegistrationSuccesEmail;
use App\Models\MailDetail;
use App\Models\User;
use App\Models\UserPointDetail;
use App\Models\UserPuchasedCourse;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\SmsService;
use App\Traits\ComissionTrait;
use App\Traits\SMSTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    use ComissionTrait, SMSTrait;

    public $userPointList = [];

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

    public function fixCommission()
    {
        return;
        $users = User::where('approved_at', '>', Carbon::parse('2025-01-17 23:44:30'))
            ->where('type', User::USER_TYPE['MAIN'])
            ->select('approved_at', 'id', 'referrer_id')->get();

        $walletDetails = WalletHistory::where('comission_type', WalletHistory::COMISSION_TYPES['DIRECT'])
            ->where('created_at', '>', Carbon::parse('2025-01-17 23:44:30'))
            ->pluck('user_id')
            ->toArray();


        foreach ($users as $user) {
            if (!in_array($user->referrer_id, $walletDetails)) {

                try {

                    DB::beginTransaction();

                    $appliedCourse = UserPuchasedCourse::with('course')->where('user_id', $user->id)
                        ->where('type', UserPuchasedCourse::TYPE['REFERRAL'])
                        ->first();
                    $amount = $appliedCourse->course?->referer_commission;

                    $ref_id = $user->referrer_id;
                    $wallet = Wallet::where('user_id', $ref_id)->first();
                    if (!isset($wallet)) {
                        $wallet = new Wallet();
                        $wallet->user_id = $ref_id;
                    }
                    $wallet->balance += $amount;
                    $wallet->save();

                    WalletHistory::create([
                        'user_id' => $ref_id,
                        'wallet_id' => $wallet->id,
                        'amount' => $amount,
                        'balance' => $wallet->balance,
                        'type' => WalletHistory::TYPE['ADDED'],
                        'comission_type' =>  WalletHistory::COMISSION_TYPES['DIRECT'],
                        'created_at' =>  $user->approved_at,
                        'updated_at' =>  $user->approved_at,
                    ]);
                    DB::commit();

                    Log::debug('addDirectComission : fix : ' . $user->id . ' ' . $ref_id . ' ' .   $amount);
                } catch (Exception $e) {
                    DB::rollback();
                    Log::debug('user_id ' . $user->id . ' ' . $e->getMessage());
                }
            }
        }



        return "success";
    }

    public function fixPoints()
    {
        $users = User::where('er_status', User::PAYMENT_STATUS['HALF'])
            ->where('payment_status', User::PAYMENT_STATUS['HALF'])
            ->where('type', User::USER_TYPE['MAIN'])
            ->where('approved_by_admin', 1)
            ->select('id', 'left_points', 'right_points', 'er_status', 'payment_status', 'path')
            ->get();


        foreach ($users as $user) {
            $this->getUpNodes($user);
        }

        $userPointDetails = UserPointDetail::get();
        foreach ($userPointDetails as $pointDetail) {
            $userData = User::where('id', $pointDetail->user_id)
                ->select('id', 'left_points', 'right_points', 'er_status', 'payment_status', 'path')
                ->first();

            $userWallet = Wallet::where('user_id', $pointDetail->user_id)->first();
            if (isset($userWallet)) {
                $pointDetail->wallet_balance = $userWallet->balance;
                $pointDetail->withdrawed = WalletHistory::where('wallet_id', $userWallet->id)
                    ->where('status', WalletHistory::STATUS['TRANSFERED'])
                    ->sum('amount');
            }
            $pointDetail->left_balance_now = $userData->left_points;
            $pointDetail->right_balance_now = $userData->right_points;

            $pointDetail->save();
        }
        return 'done';
    }

    private function getUpNodes(User $user)
    {

        $appliedCourse = UserPuchasedCourse::with('course')->where('user_id', $user->id)
            ->where('type', UserPuchasedCourse::TYPE['REFERRAL'])
            ->first();

        $amount = $appliedCourse->course?->referer_commission;
        $PATH = $user->path;

        preg_match_all('#/([^/]*)#', $PATH, $matches);  //SEPERATE PATH VARIABLES

        $idList = $matches[0];

        array_pop($idList); //REMOVE LAST VARUIABLE DUE TO ITS OWN
        $leftPointIdList = [];
        $rightPointIdList = [];
        // dd($PATH );
        // try {
        // DB::beginTransaction();

        foreach ($idList as $node) {
            $position  = strpos($node, 'P');
            $reg_no = substr($node, $position  + 1);

            preg_match_all('/\d+/', $reg_no, $ids);
            $id = $ids[0];
            // dd( $id);

            $pointDetail = UserPointDetail::where('user_id', $id[0])->first();
            if (strpos($reg_no, 'SL') !== false) {
                // dd('sss');
                Log::debug($reg_no . ' SL');
                // array_push($leftPointIdList, $id);

                if (!isset($pointDetail)) {
                    $pointDetail = new UserPointDetail();
                    $pointDetail->user_id =  $id[0];
                    $pointDetail->left = $amount;
                    $pointDetail->right = 0;
                    $pointDetail->save();
                } else {
                    $pointDetail->left += $amount;
                    $pointDetail->save();
                }
            }
            if (strpos($reg_no, 'SR') !== false) {
                Log::debug($reg_no . ' SR');
                // array_push($rightPointIdList, $id);

                if (!isset($pointDetail)) {
                    $pointDetail = new UserPointDetail();
                    $pointDetail->user_id =  $id[0];
                    $pointDetail->left = 0;
                    $pointDetail->right = $amount;

                    $pointDetail->save();
                } else {
                    $pointDetail->right += $amount;
                    $pointDetail->save();
                }
            }
        }



        // DB::commit();
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     Log::alert($e->getMessage());
        // }
        // if (sizeof($leftPointIdList)) {
        //     User::whereIn('id', $leftPointIdList)
        //         ->where('er_status', User::USER_STATUS['ER'])
        //         ->update(['left_points' => DB::raw('left_points +' . $point)]);
        //     $history->left_point_id_list = json_encode($leftPointIdList);
        // }

        // if (sizeof($rightPointIdList)) {
        //     User::whereIn('id', $rightPointIdList)
        //         ->where('er_status', User::USER_STATUS['ER'])
        //         ->update(['right_points' => DB::raw('right_points +' . $point)]);
        //     $history->right_point_id_list = json_encode($rightPointIdList);
        // }


    }
}
