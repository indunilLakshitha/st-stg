<?php

namespace App\Livewire\Wallet;

use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\Wallet as ModelsWallet;
use App\Models\WalletHistory;
use App\Services\SmsService;
use App\Traits\WalletTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Wallet extends Component
{
    use WalletTrait;

    public   $wallet, $request_amount, $walletHistory, $user, $requested_amount, $total_amount, $amount_requesting = 0, $minimum;
    public   $date_from,
        $date_to;
    public $bank_details = null;
    public $referenceId = null, $otp_number;

    public function mount()
    {
        $this->minimum = env('MINIMUM_WALLET_REQUEST_MOUNT');
        $this->user =  Auth::user();
        $this->wallet = ModelsWallet::where('user_id', $this->user->id)->first();
        $this->walletHistory = WalletHistory::where('user_id', $this->user->id)
            ->where('type', WalletHistory::TYPE['REMOVED'])
            ->orderByDesc('id')
            ->get();

        $this->requested_amount =  $this->walletHistory->where('status', WalletHistory::STATUS['REQUESTED'])
            ->where('type', '!=', WalletHistory::TYPE['ADDED'])->sum('amount');
        $this->total_amount =  $this->walletHistory->where('status', WalletHistory::STATUS['TRANSFERED'])->sum('amount');

        $this->bank_details = UserBankDetail::where('user_id', Auth::user()->id)->first();
    }

    public function render()
    {
        return view('livewire.wallet.wallet');
    }

    public function sendOtp()
    {
        $this->validate([
            'amount_requesting' => 'required|numeric|not_regex:/[@#$%^&*();><]/'
        ]);

        if (Auth::user()->type != User::USER_TYPE['MAIN']) {
            return $this->dispatch('failed_alert', ['title' => 'Invalid User']);
        }
        if ($this->amount_requesting <= 0) {
            $this->closeModal();
            return $this->dispatch('failed_alert', ['title' => 'Invalid Amount']);
        }
        if ($this->amount_requesting <= $this->minimum) {
            $this->closeModal();
            return $this->dispatch('failed_alert', ['title' => 'Minimum Requesting Amount is : ' . $this->minimum]);
        }

        if ($this->wallet->balance < $this->amount_requesting) {
            $this->closeModal();
            return $this->dispatch('failed_alert', ['title' => 'Insufficient Amount']);
        }
        $this->dispatch('close_modal', ['title' => 'Wallet Updated']);

        $this->referenceId = (new SmsService())->sendOTP(Auth::user()->mobile_no);

        if (isset($this->referenceId))
            $this->dispatch('update_modal', ['title' => 'Successfully Requested']);
    }

    public function verifyOtp()
    {
        if ((new SmsService())->verifyOTP(otp: $this->otp_number, referenceId: $this->referenceId)) {
            return $this->request();
        } else {
            $this->dispatch('failed_alert', ['title' => 'Invalid OTP']);
            $this->dispatch('close_modal_otp', ['title' => 'Wallet Updated']);
        }
    }

    public function request()

    {

        $this->deductFromWallet(wallet: $this->wallet);
        $this->setWalletHistoryWhenRequest(
            wallet: $this->wallet,
            userId: $this->user->id,
            amount: (int) $this->amount_requesting,
            type: WalletHistory::TYPE['REMOVED']
        );

        $this->closeModal();
        $this->dispatch('success_alert', ['title' => 'Successfully Requested']);
        $this->dispatch('close_modal_otp', ['title' => 'Wallet Updated']);
        $this->mount();
    }

    public function deductFromWallet(ModelsWallet $wallet)
    {
        $wallet->balance -= $this->amount_requesting;

        $wallet->save();

        $this->mount();
        $this->dispatch('success_alert', ['title' => 'Wallet Updated']);
    }

    public function filter()
    {

        $this->user =  Auth::user();
        $this->wallet = ModelsWallet::where('user_id', $this->user->id)->first();
        $walletHistory = WalletHistory::where('user_id', $this->user->id)
            ->where('type', WalletHistory::TYPE['REMOVED'])
            ->orderByDesc('id');



        $walletHistory = $walletHistory->when($this->date_from, function ($q) {
            return $q->whereDate('created_at', '>=', $this->date_from);
        });

        $walletHistory = $walletHistory->when($this->date_to, function ($q) {
            return $q->whereDate('created_at', '<=', $this->date_to);
        });

        $this->walletHistory =  $walletHistory->get();

        $this->requested_amount =  $this->walletHistory->where('status', WalletHistory::STATUS['REQUESTED'])
            ->where('type', '!=', WalletHistory::TYPE['ADDED'])->sum('amount');
        $this->total_amount =  $this->walletHistory->where('status', WalletHistory::STATUS['TRANSFERED'])->sum('amount');
    }

    public function closeModal()
    {
        $this->dispatch('close_modal', ['title' => 'Wallet Updated']);
    }

    public function cansel($id)
    {

        $history = WalletHistory::find($id);
        if ($history->status != WalletHistory::STATUS['REQUESTED']) {
            return;
        }
        if (!isset($history))
            return $this->dispatch('failed_alert', ['title' => 'Invalid Request']);

        if ($history->user_id != Auth::user()->id)
            return $this->dispatch('failed_alert', ['title' => 'Invalid Request']);


        try {
            DB::beginTransaction();
            $history->delete();
            $wallet = ModelsWallet::where('id', $history->wallet_id)
                ->where('user_id', Auth::user()->id)
                ->first();
            $wallet->balance += $history->amount;
            $wallet->save();
            DB::commit();
            $this->dispatch('success_alert', ['title' => 'Canseled Successfully']);
            return $this->mount();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->dispatch('failed_alert', ['title' => 'Failed . Please Contact Admin']);
        }
    }
}
