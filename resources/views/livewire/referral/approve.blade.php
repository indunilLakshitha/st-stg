<div>
    <livewire:comp.breadcumb title="REFERRALS" section="User" sub="Referrals" action="Approve">
        <div class="edash-content-section row g-3 g-md-4">
            <!-- Start:: Defaults -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Approve -
                            {{ $user->first_name . ' ' . $user->last_name }}</h4>
                    </div>
                    <div class="card-body">
                        <form class="row g-4" wire:submit="approve">
                            <div class="col-md-3 form-group col-sm-6">
                                <label for="validationDefault04" class="form-label">Path</label>
                                {{-- <select class="form-select" id="validationDefault04" required wire:model='assigned_to'
                                    wire:change='selectPath'>
                                    <option selected value="">Choose...</option>
                                    @foreach ($path_list as $p)
                                        <option value="{{ $p->id }}">{{ $p->id }}
                                        </option>
                                    @endforeach
                                </select> --}}
                                <input type="text" class="form-control" wire:input='getPathBySearch()'
                                    wire:focus='getPathBySearch()' wire:model='keyword' />
                                <div class="panel-footer ">
                                    <ul class="list-group cus-list">
                                        @foreach ($filteredPaths as $p)
                                            <li class="list-group-item" wire:click='setPathValue({{ $p }})'>
                                                {{ $p->first_name . ' - ' . $p->last_name . ' ' . $p->type . ' ' . $p->id }}
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="validationDefault04" class="form-label">Agent</label>
                                <select class="form-select" id="validationDefault04" required
                                    wire:model='assigned_user_side'>
                                    <option selected value="">Choose...</option>
                                    @if ($A1_active)
                                        <option value="A1">Agent A1</option>
                                    @endif
                                    @if ($A2_active)
                                        <option value="A2">Agent A2</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault01" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="validationDefault01"
                                    wire:model='first_name' disabled />
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault01" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="validationDefault01"
                                    wire:model='last_name' disabled />
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault01" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="validationDefault01" wire:model='reg_no'
                                    disabled />
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefault02" class="form-label">Contact No</label>
                                <input type="text" class="form-control" id="validationDefault02"
                                    wire:model='mobile_no' disabled />
                            </div>
                            <div class="col-md-4">
                                <label for="validationDefaultUsername" class="form-label">Email ID</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="inputGroupPrepend2">@</span>
                                    <input type="text" class="form-control" id="validationDefaultUsername"
                                        wire:model='email' aria-describedby="inputGroupPrepend2" required disabled />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label">NIC No</label>
                                <input type="text" class="form-control" id="validationDefault03" required disabled
                                    wire:model='nic' />
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label">Address</label>
                                <input type="text" class="form-control" id="validationDefault03" required disabled
                                    wire:model='address' />
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label">Course</label>
                                <input type="text" class="form-control" id="validationDefault03" disabled
                                    wire:model='course' />
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="validationDefault04" class="form-label">TYPE</label>
                                <select class="form-select" id="validationDefault04" required wire:model='payment_type' >
                                    <option value="FULL" selected>FULL</option>
                                    <option value="HALF">HALF</option>
                                </select>
                            </div> --}}
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit"{{ !$submit_active ? 'disabled' : '' }}>
                                    Approve User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <style>
                .panel-footer {
                    position: absolute;
                    width: calc(100% - 30px);
                    z-index: 9;
                }

                .cus-list {
                    max-height: 400px !important;
                    overflow-y:scroll
                }

                .form-group {
                    position: relative;
                }

                .remove-padding {
                    padding: 5px 5px 5px 5px !important;
                }
            </style>
        </div>
