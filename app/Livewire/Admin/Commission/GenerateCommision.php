<?php

namespace App\Livewire\Admin\Commission;

use App\Jobs\ComissionGenerateJob;
use App\Models\ComissionGeneratHistory;
use App\Models\MasterData;
use App\Traits\ActivityLogTrait;
use App\Usecases\Comission\ComissionGenerateUsecase;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class GenerateCommision extends Component
{
    use ActivityLogTrait;

    public $password, $history, $userId;

    public function mount()
    {
        $this->history = ComissionGeneratHistory::with('user')->get();
    }

    public function render()
    {
        return view('livewire.admin.commission.generate-commision');
    }

    public function generateCommision()
    {
        $this->validate([
            'password' => 'required',
        ]);
        $masterData = MasterData::first();
        $this->userId = Auth::user()->id;
        if (Hash::check($this->password, $masterData->comission_password)) {
            try {

                $this->startGenerateComissions();
                return $this->dispatch('success_alert', ['title' => 'Comission Generating Successfully Started.']);
            } catch (Exception $e) {

                $this->addToLog(msg: 'startGenerateComissions FAILD :' . $e->getMessage());
                return $this->dispatch('failed_alert', ['title' => 'Invalid Credentials']);

            }
        } else {

            $this->addToLog(msg: 'startGenerateComissions FAILD : Invalid Credentials');
            return $this->dispatch('failed_alert', ['title' => 'Invalid Credentials']);
        }
    }

    private function startGenerateComissions()
    {
        // (new ComissionGenerateUsecase())->handle(userId: $this->userId);
        return  dispatch(new ComissionGenerateJob(userId: Auth::user()->id));
    }
}
