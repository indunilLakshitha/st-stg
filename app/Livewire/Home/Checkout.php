<?php

namespace App\Livewire\Home;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Checkout extends Component
{
    public $user_id;

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $user = User::find($this->user_id);
        if (!isset($user))
            return redirect()->route('index');

        if ($user->paid)
            return redirect()->route('index');
    }
    public function render()
    {
        return view('livewire.home.checkout');
    }

    public function setPaid()
    {
        $user = User::find($this->user_id);
        $user->paid = true;
        $user->paid_at = Carbon::now();
        $user->save();

        // $this->dispatch('registered_with_id', [
        //     'title' => 'Use ' . $this->user_id . ' As Your USER ID When Login',
        //     'detail' => 'Ok, My USER ID is : ' . $this->user_id
        // ]);

        return redirect()->route('thankYou', [$this->user_id]);
    }
}
