<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use WithPagination;
    public $page = 1;

    public  $courses, $items, $search, $selected_course;
    public $active_status, $status, $type = 1;
    protected $queryString = ['type', 'status', 'active_status', 'search', 'selected_course'];



    public function updating($field)
    {
        // $this->resetErrorBag();
        // $this->resetPage(); // Reset pagination on first interaction
    }

    public function mount()
    {
        // $items = User::with('referrer', 'purchase.course')
        //     ->where('is_admin', false)
        //     ->where('type', User::MAIN)
        //     ->where('approved_by_referrer', true)
        //     ->paginate(10);
        // // ->get();
        // $this->items = $items;
        // $this->customers = $items->items();
        // // dd($items);

        $this->courses = Course::select('name', 'id')->get();
    }

    public function render()
    {
        $this->page = 1;
        $customers = User::query()->with('referrer', 'purchase.course')
            ->where('is_admin', false)

            ->where('approved_by_referrer', true)
            ->when($this->active_status, function ($q) {
                return $q->where('active_status', $this->active_status);
            })
            ->when($this->status, function ($q) {
                return $q->where('er_status', $this->status);
            })
            ->when($this->selected_course, function ($q) {
                return $q->whereHas('purchase.course', function ($query) {
                    $query->where('id', $this->selected_course);
                });
            })
            ->when($this->type, function ($q) {

                if ($this->type == 1)
                    return $q->where('type', User::MAIN);

                if ($this->type == 2)
                    return $q->where('type', '!=', User::MAIN);
            })
            ->when($this->search, function ($q) {
                if (strlen($this->search) >= 3) {
                    return $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('id', 'like', '%' . $this->search . '%')
                        ->orWhere('nic', 'like', '%' . $this->search . '%');
                }
            })
            ->paginate(10);
        return view('livewire.admin.customers.customers', ['customers' => $customers]);
    }

    public function getPaginatedItemsProperty()
    {
        return array_slice($this->items, ($this->page - 1) * 10, 10);
    }

    public function filter()
    {
        $this->render();
        // $customers = User::query()->where('approved_by_referrer', true);
        // $customers = $customers->when($this->active_status, function ($q) {
        //     return $q->where('active_status', $this->active_status);
        // });
        // $customers = $customers->when($this->status, function ($q) {
        //     return $q->where('er_status', $this->status);
        // });
        // $customers = $customers->when($this->type, function ($q) {
        //     if ($this->type == 1)
        //         return $q->where('type', User::MAIN);

        //     if ($this->type == 2)
        //         return $q->where('type', '!=', User::MAIN);
        // });

        // $this->customers = [];
        // $this->customers =  $customers->get();
    }

    public function block($id)
    {
        $customer = User::where('id', $id)->first();

        if (Auth::user()->is_admin) {
            $customer->active_status = User::BLOCKED;
            $customer->save();

            // session()->flash('message', 'User successfully Blocked.');
            $this->dispatch('success_alert', ['title' => 'User successfully Blocked']);

            return $this->mount();
        }

        return abort(404);
    }

    public function unblock($id)
    {
        $customer = User::where('id', $id)->first();

        if (Auth::user()->is_admin) {
            $customer->active_status = User::UNBLOCKED;
            $customer->save();

            // session()->flash('message', 'User successfully Unblocked.');
            $this->dispatch('success_alert', ['title' => 'User successfully Unblocked']);

            return $this->mount();
        }

        return abort(404);
    }

    public function erActivate($id)
    {
        $customer = User::where('id', $id)->first();

        if (Auth::user()->is_admin) {
            $customer->er_status = User::USER_STATUS['ER'];
            $customer->my_left_a2_active = true;
            $customer->my_right_a2_active = true;
            $customer->save();

            // session()->flash('message', 'User successfully Unblocked.');
            $this->dispatch('success_alert', ['title' => 'User successfully ER Activated']);
            return $this->mount();
        }

        return abort(404);
    }

    public function fullActivate($id)
    {
        $customer = User::where('id', $id)->first();

        if (Auth::user()->is_admin) {
            $customer->er_status = User::USER_STATUS['FULL'];
            $customer->save();

            // session()->flash('message', 'User successfully Unblocked.');
            $this->dispatch('success_alert', ['title' => 'User successfully Full Activated']);
            return $this->mount();
        }

        return abort(404);
    }

    public function delete($id)
    {
        $customer = User::where('id', $id)->first();

        if (Auth::user()->is_admin && !$customer->approved_by_admin) {
            $customer->forceDelete();

            $this->dispatch('success_alert', ['title' => 'User successfully Deleted']);
            return $this->mount();
        }

        return abort(404);
    }

    public function changeTreeStatus($id)
    {
        $customer = User::where('id', $id)->first();
        if (Auth::user()->is_admin) {

            if ($customer->is_tree_enabled) {
                $customer->is_tree_enabled = false;
            } else {
                $customer->is_tree_enabled = true;
            }

            $customer->save();

            // session()->flash('message', 'User successfully Updated.');
            $this->dispatch('success_alert', ['title' => 'User successfully Updated']);

            return $this->mount();
        }

        return abort(404);
    }

    public function showHover()
    {
        // dd('sss');
    }
}
