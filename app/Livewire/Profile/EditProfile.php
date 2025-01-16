<?php

namespace App\Livewire\Profile;

use App\Models\Branch;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\UserBenificiaryDetail;
use App\Rules\MobileNoalidation;
use App\Rules\NICValidation;
use App\Services\UserBankService;
use App\Services\UserService;
use App\Traits\LabelTrait;
use App\Traits\UniqueIdTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use function PHPUnit\Framework\returnValueMap;

class EditProfile extends Component
{
    use UniqueIdTrait, LabelTrait;

    public $mainUser;
    public $name, $first_name, $last_name, $reg_no, $type, $address, $email, $mobile_no, $dob, $nic, $gender, $customer_branch;
    public $bank_name, $branch, $holder_name, $account_number;
    public $benificiary_name, $relationship, $benificiary_contact_no, $account_type;
    public $hasPermission = false;
    public $passwordMode = false;
    public $basicMode = true;
    public $bankEnabled = false;
    public $right_points, $left_points, $user;

    public $password, $confirm_password;

    public function mount($userId)
    {
        $loggedUser = Auth::user();
        $this->hasPermission = $loggedUser->is_admin;
        $user = User::where('users.id', $userId)
            ->leftjoin('user_bank_details', 'user_bank_details.user_id', 'users.id')
            ->leftjoin('user_benificiary_details', 'user_benificiary_details.user_id', 'users.id')
            ->select(
                'users.*',
                'user_bank_details.bank_name',
                'user_bank_details.branch',
                'users.branch as customer_branch',
                'user_bank_details.holder_name',
                'user_bank_details.account_number',
                'user_benificiary_details.name as benificiary_name',
                'user_benificiary_details.relationship',
                'user_benificiary_details.contact_no as benificiary_contact_no',
            )
            ->first();
        $this->mainUser = $user;

        $this->name = $user->name;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->reg_no = $user->reg_no;
        $this->type = $user->type;
        $this->address = $user->address;
        $this->email = $user->email;
        $this->mobile_no = $user->mobile_no;
        $this->gender = $user->gender;
        $this->dob = $user->dob;
        $this->nic = $user->nic;
        $this->customer_branch = $user->customer_branch;

        $this->benificiary_name = $user->benificiary_name;
        $this->relationship = $user->relationship;
        $this->benificiary_contact_no = $user->benificiary_contact_no;
        $this->nic = $user->nic;

        $this->bank_name = $user->bank_name;
        $this->branch = $user->branch;
        $this->holder_name = $user->holder_name;
        $this->account_number = $user->account_number;

        $this->left_points = $user->left_points;
        $this->right_points = $user->right_points;

        if ($user->er_status == 4 && !isset($this->bank_name)) {
            $this->bankEnabled = true;
        }

        $this->account_type = $this->getUserTypeLabel($user->type);
      
    }



    public function savePersonal()
    {
        $this->validate(
            [
                'first_name' => 'required|not_regex:/[@#$%^&*();><]/',
                'last_name' => 'required|not_regex:/[@#$%^&*();><]/',
                'email' => 'required|email',
                'mobile_no' => ['required', 'numeric', new MobileNoalidation],
                'nic' =>  ['required', new NICValidation],
            ],
        );

        try {
            if (!$this->hasPermission) {
                return redirect()->route('dashboard');
            }

            if (!in_array($this->gender, User::GENDER_LIST)) {
                return $this->dispatch('failed_alert', ['title' => 'Invalid Gender']);
            }

            if (!in_array($this->customer_branch, Branch::BRANCHES)) {
                return $this->dispatch('failed_alert', ['title' => 'Invalid Branch']);
            }


            DB::beginTransaction();

            $user = $this->mainUser;

            if ($this->hasPermission) {
                $user->name = $this->first_name . ' ' . $this->last_name;
                $user->first_name = $this->first_name;
                $user->last_name = $this->last_name;
                $user->dob = $this->dob;
                $user->gender = $this->gender;

                $user->branch = $this->customer_branch;

                User::where('parent_id', $user->id)->update([
                    'name' => $user->name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ]);

                $user->address = $this->address;
                $user->email = $this->email;
                $user->mobile_no = $this->mobile_no;
                $user->nic = $this->nic;
                User::where('parent_id', $user->id)->update([
                    'address' => $user->address,
                    'email' => $user->email,
                    'mobile_no' => $user->mobile_no,
                    'nic' => $user->nic,
                ]);
            }


            $user->save();

            DB::commit();

            $this->mount($user->id);

            return $this->dispatch('success_alert', ['title' => 'Personal Details successfully Updated']);
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('savePersonal :: ' . $e->getMessage());
        }
    }

    public function saveBank()
    {
        if (!$this->bankEnabled) {
            return redirect()->route('dashboard');
        }

        $this->validate(
            [
                'account_number' => 'required|not_regex:/[@#$%^&*();><]/',
                'bank_name' => 'required|not_regex:/[@#$%^&*();><]/',
                'holder_name' => 'required|not_regex:/[@#$%^&*();><]/',
                'branch' => 'required|not_regex:/[@#$%^&*();><]/'
            ],
        );

        try {
            DB::beginTransaction();
            $user = $this->mainUser;

            if ($this->bankEnabled) {
                if ($this->account_number) {
                    UserBankDetail::where('user_id', $user->id)->delete();
                    (new UserBankService())->addBankToUser(
                        account_number: $this->account_number,
                        bank_name: $this->bank_name,
                        holder_name: $this->holder_name,
                        branch: $this->branch,
                        user_id: $user->id
                    );
                }
            }

            DB::commit();

            $this->mount($user->id);

            return $this->dispatch('success_alert', ['title' => 'Bank Details successfully Updated']);
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('saveBank :: ' . $e->getMessage());
        }
    }

    public function updatePoints()
    {
        if (!$this->hasPermission) {
            return redirect()->route('dashboard');
        }
        try {
            DB::beginTransaction();
            $user = $this->mainUser;

            if (Auth::user()->is_admin) {
                $user->right_points = $this->right_points;
                $user->left_points = $this->left_points;
                $user->save();
            }

            DB::commit();

            $this->mount($user->id);

            return $this->dispatch('success_alert', ['title' => 'Point Details successfully Updated']);
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('updatePoints :: ' . $e->getMessage());
        }
    }

    public function saveBenificiary()
    {

        $this->validate(
            [
                'benificiary_contact_no' => ['required', 'not_regex:/[@#$%^&*();><]/', new MobileNoalidation],
                'relationship' => 'required|not_regex:/[@#$%^&*();><]/',
                'benificiary_name' => 'required|not_regex:/[@#$%^&*();><]/',
            ],
        );

        try {
            DB::beginTransaction();
            $user = $this->mainUser;

            UserBenificiaryDetail::where('user_id', $user->id)->delete();
            (new UserService())->addBenificiearyToUser(
                contact_no: $this->benificiary_contact_no ? $this->benificiary_contact_no : '',
                relationship: $this->relationship ? $this->relationship : '',
                name: $this->benificiary_name ? $this->benificiary_name : '',
                user_id: $user->id
            );

            DB::commit();

            $this->mount($user->id);

            return $this->dispatch('success_alert', ['title' => 'Benificiary Details successfully Updated']);
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('saveBenificiary :: ' . $e->getMessage());
        }
    }

    public function clear()
    {
        $this->name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->reg_no = "";
        $this->type = "";
        $this->address = "";
        $this->email = "";
        $this->mobile_no = "";
        $this->dob = "";
        $this->nic = "";
        $this->bank_name = "";
        $this->branch = "";
        $this->holder_name = "";
        $this->account_number = "";
        $this->benificiary_name = "";
        $this->relationship = "";
        $this->benificiary_contact_no = "";
    }

    public function clearPasswords()
    {
        $this->confirm_password = "";
        $this->password = "";
    }


    public function render()
    {
        return view('livewire.profile.edit-profile');
    }

    public function showPassword()
    {
        $this->basicMode = false;
        $this->passwordMode = true;
    }

    public function showBasic()
    {
        $this->basicMode = true;
        $this->passwordMode = false;
    }

    public function editPassword()
    {
        $this->validate([
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6'
        ]);

        $user = $this->mainUser;
        $user->password =  Hash::make($this->password);
        $user->save();

        $this->clearPasswords();

        // return session()->flash('message', 'Password successfully Updated.');
        return $this->dispatch('success_alert', ['title' => 'Password successfully Updated']);
    }
}
