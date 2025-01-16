<div>
    <!--! Start:: Breadcumb !-->
    <livewire:comp.breadcumb title="SETTINGS" section="Admin" sub="Settings" action="All">
        <!--! End:: Breadcumb !-->
        <div class="edash-content-section row g-3 g-md-4">

            <div class="col-6">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <!-- Start:: Zero Config -->
            <div class="col-12">
                <div class="card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="card-header">
                            <h4 class="card-title">Settings</h4>
                        </div>
                        <div class="card-header">
                            {{-- <a href="{{ route('admin.customer.create') }}" class="btn btn-md btn-primary">ADD</a> --}}
                        </div>
                    </div>
                    <div class="card-table table-responsive">

                        <table id="zeroConfig" class="table mb-0">
                            <thead>
                                <tr>
                                    <th>COMMISSION GENERATE</th>
                                    <th>REGISTRATION SUCCESS</th>
                                    <th>ADMIN APPROVED</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if ($commission_enabled == 1)
                                            <button class="btn btn-danger" type="button"
                                                wire:click='changeCommissionGenerate()'>
                                                DISABLE
                                            </button>
                                        @else
                                            <button class="btn btn-primary" type="button"
                                                wire:click='changeCommissionGenerate()'>
                                                ENABLE
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reg_success_mail_enabled)
                                            <button class="btn btn-danger" type="button" wire:click='changeRegEmail()'>
                                                DISABLEMAIL
                                            </button>
                                        @else
                                            <button class="btn btn-primary" type="button"
                                                wire:click='changeRegEmail()'>
                                                ENABLE MAIL
                                            </button>
                                        @endif
                                        @if ($reg_success_sms_enabled)
                                            <button class="btn btn-danger" type="button" wire:click='changeRegSms()'>
                                                DISABLE SMS
                                            </button>
                                        @else
                                            <button class="btn btn-primary" type="button" wire:click='changeRegSms()'>
                                                ENABLE SMS
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($approved_mail_enabled)
                                            <button class="btn btn-danger" type="button"
                                                wire:click='changeAdminApproveEmail()'>
                                                DISABLE MAIL
                                            </button>
                                        @else
                                            <button class="btn btn-primary" type="button"
                                                wire:click='changeAdminApproveEmail()'>
                                                ENABLE MAIL
                                            </button>
                                        @endif
                                        @if ($approved_sms_enabled)
                                            <button class="btn btn-danger" type="button"
                                                wire:click='changeAdminApproveSms()'>
                                                DISABLE SMS
                                            </button>
                                        @else
                                            <button class="btn btn-primary" type="button"
                                                wire:click='changeAdminApproveSms()'>
                                                ENABLE SMS
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>COMMISSION GENERATE</th>
                                    <th>REGISTRATION SUCCESS</th>
                                    <th>ADMIN APPROVED</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End:: Zero Config -->
        </div>
</div>
