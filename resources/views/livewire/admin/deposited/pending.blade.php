<div>
    <!--! Start:: Breadcumb !-->
    <livewire:comp.breadcumb title="Withdrawal Requests" section="Admin" sub="Withdrawal" action="Requests">
        <!--! End:: Breadcumb !-->

        <div class="edash-content-section row g-3 g-md-4">
            <div class="row  g-md-4">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between pd-5">
                                <div class="avatar avatar-xl rounded bg-warning-subtle text-warning">
                                    <i class="fi fi-rr-chart-histogram"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}{{ number_format($total_withdrawal_requests, 2) }}</h4>
                                    <span class="fs-13 text-muted">Total Withdrawal Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between pd-5">
                                <div class="avatar avatar-xl rounded bg-danger-subtle text-danger">
                                    <i class="fi fi-rr-stats"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}{{ number_format($total_pending_deposites, 2) }} </h4>
                                    <span class="fs-13 text-muted">Total Pending Deposits</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between pd-5">
                                <div class="avatar avatar-xl rounded bg-success-subtle text-success">
                                    <i class="fi fi-rr-sack-dollar"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}{{ number_format($total_deposites, 2) }}</h4>
                                    <span class="fs-13 text-muted"> Total Deposits</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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
                            <h4 class="card-title">Requested Amounts</h4>
                        </div>

                    </div>
                    <div class="card-table table-responsive">

                        <table id="dataTablesExport" class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Requested Amount</th>
                                    <th>Balance ( Rs )</th>
                                    <th>Requested At</th>
                                    <th>Bank Details</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendings as $pending)
                                    <tr>
                                        <td>{{ $pending->id }}</td>
                                        <td>{{ $pending->user?->name }}</td>
                                        <td>{{ number_format($pending->amount, 2) }}</td>
                                        <td>{{ number_format($pending->wallet?->balance, 2) }}</td>
                                        <td>{{ $pending->requested_at }}</td>
                                        <td>
                                            <dl class="dl-horizontal mb-0 remove-margin ">
                                                <dt class="remove-margin">Account Holder :</dt>
                                                <dd class="remove-margin">{{ $pending->user?->bank?->holder_name }}
                                                    @if (isset($pending->user?->bank?->holder_name))
                                                        <button class=" btn p-0"
                                                            wire:click='copyBankDetails( {{ $pending->user?->bank }},1 )'>
                                                            <i class="fi fi-rr-layers"></i>

                                                        </button>
                                                    @endif
                                                </dd>
                                                <dt class="remove-margin">Acc No:</dt>
                                                <dd class="remove-margin">{{ $pending->user?->bank?->account_number }}
                                                    @if (isset($pending->user?->bank?->holder_name))
                                                        <button class=" btn p-0"
                                                            wire:click='copyBankDetails({{ $pending->user?->bank }},2)'>
                                                            <i class="fi fi-rr-layers"></i>

                                                        </button>
                                                    @endif
                                                </dd>
                                                <dt class="remove-margin">Bank:</dt>
                                                <dd class="remove-margin">{{ $pending->user?->bank?->bank_name }}
                                                    @if (isset($pending->user?->bank?->holder_name))
                                                        <button class=" btn p-0"
                                                            wire:click='copyBankDetails({{ $pending->user?->bank }},3)'>
                                                            <i class="fi fi-rr-layers"></i>

                                                        </button>
                                                    @endif
                                                </dd>
                                                <dt class="remove-margin">Branch:</dt>
                                                <dd class="remove-margin">{{ $pending->user?->bank?->branch }}
                                                    @if (isset($pending->user?->bank?->holder_name))
                                                        <button class=" btn p-0"
                                                            wire:click='copyBankDetails({{ $pending->user?->bank }},4)'>
                                                            <i class="fi fi-rr-layers"></i>

                                                        </button>
                                                    @endif
                                                </dd>


                                            </dl>
                                        </td>

                                        <td>

                                            {{-- <button class="btn btn-danger" wire:click='approve({{ $user->id }})'
                                                wire:confirm="Are you sure you want to Approve this customer?"
                                                type="button">
                                                APPROVE
                                            </button> --}}

                                            <button class="btn btn-primary" wire:click='approve({{ $pending->id }})'
                                                wire:confirm="Are you sure you want to Pay this?" type="button">
                                                APPROVE
                                            </button>
                                            <button class="btn btn-danger" wire:click='cansel({{ $pending->id }})'
                                                wire:confirm="Are you sure you want to Cancel this Request?"
                                                type="button">
                                                CANCEL
                                            </button>
                                        </td>

                                    </tr>
                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Requested Amount</th>
                                    <th>Balance (LKR)</th>
                                    <th>Requested At</th>
                                    <th>Bank Details</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End:: Zero Config -->
        </div>

        <style>
            .remove-margin {
                margin-bottom: 0 !important;
            }
        </style>
</div>

<script>
    window.addEventListener('bank_set', function(e) {
        copy(e);
    });

    function copy(data) {

        var text = data.detail[0].text;
        navigator.clipboard.writeText(text);

        Swal.mixin({
            toast: !0,
            position: "top-end",
            showConfirmButton: !1,
            timer: 3e3,
            timerProgressBar: !0,
            didOpen: function didOpen(t) {
                t.addEventListener("mouseenter", Swal.stopTimer), t.addEventListener("mouseleave",
                    Swal.resumeTimer);
            }
        }).fire({
            icon: "success",
            title: text
        });
    }
</script>
