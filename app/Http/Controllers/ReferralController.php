<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CheckAccessTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    use CheckAccessTrait;

    public function pending()
    {
        return view('User.Referral.pending');
    }

    public function approve($id)
    {

        $this->isReferralOwner(id: $id);

        return view('User.Referral.approve', compact('id'));
    }
}
