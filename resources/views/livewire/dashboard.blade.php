<!--! ================================================================ !-->
<!--! Start:: Page Content !-->
<!--! ================================================================ !-->
<div class="" id="">
    <!--! Start:: Breadcumb !-->
    <div class="edash-content-breadcumb row mb-4 mb-md-6 pt-md-2">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h4 fw-semibold text-dark">Summery</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">

                            <li class="breadcrumb-item">
                                <a href="javascript:void(0;)">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Summery
                            </li>
                        </ol>
                    </nav>
                </div>
                {{-- <div class="d-flex align-items-center gap-2">
                    <a href="javascript:void(0);" class="btn btn-md bg-white text-black d-none d-sm-flex">New
                        Project</a>
                    <a href="javascript:void(0);" class="btn btn-md btn-primary">Reports</a>
                </div> --}}
            </div>
        </div>
    </div>
    <!--! End:: Breadcumb !-->
    <!--! Start:: Content Section !-->
    <div class="edash-content-section row g-3 g-md-4">
        <!-- Start:: Earning & Expense -->


        <div class="col-xxl-8">
            <div class="card mb-3 mb-md-4">
                <div class="card-body">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fs-18">Overview</h5>
                            <p class="fs-13 text-muted mb-0">

                            </p>
                        </div>
                        {{-- <a href="javascript:void(0);" class="icon-link icon-link-hover link-primary">
                            <span>View Details</span>
                            <i class="fi fi-rr-arrow-small-right bi"></i>
                        </a> --}}
                    </div>

                    @if ($user->is_admin)
                        <div class="row g-3 g-md-4">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between pd-5">
                                            <div class="avatar avatar-xl rounded bg-warning-subtle text-warning">
                                                <i class="fi fi-rr-chart-histogram"></i>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalComission, 2) }}
                                                </h4>
                                                <span class="fs-13 text-muted">Total Commission</span>
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
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalDirectSaleComission, 2) }}</h4>
                                                <span class="fs-13 text-muted">Direct Sale Commission</span>
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
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalGroupSaleComission, 2) }}</h4>
                                                <span class="fs-13 text-muted"> Group Sale Commission</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between pd-5">
                                            <div class="avatar avatar-xl rounded bg-warning-subtle text-warning">
                                                <i class="fi fi-br-check"></i>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalPaidComission, 2) }}
                                                </h4>
                                                <span class="fs-13 text-muted">Total Withdrawal Request
                                                </span>
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
                                                <i class="fi fi-rr-settings-sliders"></i>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalUnpaidComission, 2) }}</h4>
                                                <span class="fs-13 text-muted">Total Unpaid Withdrawals</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <a href="{{ route('referrals.pending') }}">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between pd-5">
                                                <div class="avatar avatar-xl rounded bg-success-subtle text-success">
                                                    <i class="fi fi-ss-users"></i>
                                                </div>
                                                <div class="text-end">
                                                    <h4 class="fs-18 fw-semibold">{{ $registrationRequests }}</h4>
                                                    <span class="fs-13 text-muted">Registration Requests</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="row g-3 g-md-4">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between pd-5">
                                            <div class="avatar avatar-xl rounded bg-warning-subtle text-warning">
                                                <i class="fi fi-rr-chart-histogram"></i>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($directEarnings, 2) }}
                                                </h4>
                                                <span class="fs-13 text-muted">Direct Earnings</span>
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
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($groupEarnings, 2) }}</h4>
                                                <span class="fs-13 text-muted">Group Earning</span>
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
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalEarning, 2) }}</h4>
                                                <span class="fs-13 text-muted"> Total Earnings </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between pd-5">
                                            <div class="avatar avatar-xl rounded bg-warning-subtle text-warning">
                                                <i class="fi fi-br-check"></i>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($totalWithdrawals, 2) }}
                                                </h4>
                                                <span class="fs-13 text-muted">Total Withdrawals

                                                </span>
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
                                                <i class="fi fi-rr-settings-sliders"></i>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fs-18 fw-semibold">{{env('CURRENCY')}}
                                                    {{ number_format($accountBalance, 2) }}</h4>
                                                <span class="fs-13 text-muted"> Account Balance
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <a href="{{ route('referrals.pending') }}">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between pd-5">
                                                <div class="avatar avatar-xl rounded bg-success-subtle text-success">
                                                    <i class="fi fi-ss-users"></i>
                                                </div>
                                                <div class="text-end">
                                                    <h4 class="fs-18 fw-semibold">{{ $registrationRequests }}</h4>
                                                    <span class="fs-13 text-muted">Registration Requests</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


        </div>

        <div class="col-xxl-4">
            <!-- card Start:: -->
            <div class="card mb-3 mb-md-4">
                <div class="card-body">
                    <div class="">
                        <div class="">
                            {{-- <p class="fs-13 text-muted text-truncate col">
                                Monthly performance reports
                            </p> --}}
                            <div class="">
                                <h3 class="fs-13 mb-1">

                                    {{-- <span class="fs-18 fw-semibold text-primary mb-2 d-inline-block">$5.65K</span> --}}
                                </h3>
                                <h5 class="card-title fw-semibold mb-4">Personal Details</h5>
                                <div class="card mb-3 mb-md-4 border-0">
                                    <div class="card-body p-0">

                                        <dl class="dl-horizontal mb-0">
                                            <dt>Name :</dt>
                                            <dd>{{ $user->name }}</dd>
                                            <dt>User ID :</dt>
                                            <dd>{{ $user->reg_no }}</dd>
                                            <dt>Contact No :</dt>
                                            <dd>{{ $user->mobile_no }}</dd>
                                            <dt>Position :</dt>
                                            <dd>26 May, 2000</dd>
                                            <dt>Status:</dt>
                                            <dd>
                                                @if ($user->er_status == 1)
                                                    NONE
                                                @elseif ($user->er_status == 2)
                                                    HALF
                                                @elseif ($user->er_status == 3)
                                                    FULL
                                                @elseif ($user->er_status == 4)
                                                    Representative
                                                @endif
                                            </dd>

                                        </dl>
                                    </div>
                                </div>
                                @if ($user->er_status == 4)
                                    <input type="hidden" id="ref_link" value="{{ $referral_link }}">
                                    <button type="button" @click='copy()' class="btn btn-md btn-primary col-12">Copy
                                        Referral Link</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card End:: -->

        </div>

        <!-- row End:: -->
        @if ($user->is_admin)
            <!-- Start:: Multiple Bar -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Income Chart</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="multipleBarAdmin" class="ht-400"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Registration Records</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="multipleBarAdminRegistrations" class="ht-400"></canvas>
                    </div>
                </div>
            </div>
            <!-- End:: Multiple Bar -->
        @else
            <!-- Start:: Multiple Bar -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Income Chart</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="multipleBar" class="ht-400"></canvas>
                    </div>
                </div>
            </div>
            <!-- End:: Multiple Bar -->

            <!-- Start:: Pie Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Status</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart1" class="ht-400"></canvas>
                    </div>
                </div>
            </div>
            <!-- End:: Pie Chart -->
        @endif


        <style>
            dl.dl-horizontal dt {
                margin-bottom: 9px;
            }

            dl.dl-horizontal dd {
                margin-bottom: 9px;

            }
        </style>
    </div>
</div>




<!--! ================================================================ !-->
<!--! End:: Page Content !-->
<!--! ================================================================ !-->
<script>
    window.onload = function() {
        var user = {!! json_encode($user ?? [], JSON_HEX_TAG) !!};
        if (user.is_admin == 0) {
            initPieChart({!! json_encode($userStatus ?? [], JSON_HEX_TAG) !!});
            initMultipleBar()

        } else {
            initMultipleBarAdmin()
            initMultipleBarAdminRegistrations()
        }
    };

    function copy() {

        var text = '{{ $referral_link }}';
        navigator.clipboard.writeText(text);
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: text,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function initMultipleBar() {
        var e = document.getElementById("multipleBar");
        var months = {!! json_encode($months ?? [], JSON_HEX_TAG) !!};
        var amounts = {!! json_encode($income_amount ?? [], JSON_HEX_TAG) !!};
        var paid_amounts = {!! json_encode($income_paid_amount ?? [], JSON_HEX_TAG) !!};

        new Chart(e, {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Income",
                    data: amounts,
                    backgroundColor: "#3E97FF"
                }, {
                    label: "Paid",
                    data: paid_amounts,
                    backgroundColor: "#E1E3EA"
                }]
            },
            options: {
                responsive: !0,
                barPercentage: .75,
                categoryPercentage: .5,
                maintainAspectRatio: !1,
                scales: {
                    y: {
                        grid: {
                            display: !1
                        },
                        border: {
                            display: !1
                        },
                        ticks: {
                            stepSize: 15,
                            color: "#6c757d",
                            font: {
                                family: "Inter, sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: !1
                        },
                        border: {
                            display: !1
                        },
                        ticks: {
                            color: "#6c757d",
                            font: {
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                },
                elements: {
                    bar: {
                        borderRadius: 3
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: !0,
                            font: {
                                color: "#6c757d",
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                }
            }
        });
    }

    function initPieChart(data) {

        var e = document.getElementById("pieChart1");

        new Chart(e, {
            type: "pie",
            data: {
                labels: ["NONE", "HALF", "FULL", "ER"],
                datasets: [{
                    data: [data['NONE'], data['HALF'], data['FULL'], data['ER']],
                    backgroundColor: ["#3454d1", "#e83e8c", "#e49e3d",
                        "#3dc7be"
                    ]
                }]
            },
            options: {
                cutout: 0,
                responsive: !0,
                maintainAspectRatio: !1,
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            usePointStyle: !0,
                            font: {
                                color: "#6c757d",
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                }
            }
        });
    }

    function initMultipleBarAdmin() {
        var e = document.getElementById("multipleBarAdmin");
        var months = {!! json_encode($months ?? [], JSON_HEX_TAG) !!};
        var amounts = {!! json_encode($income_amount ?? [], JSON_HEX_TAG) !!};
        var paid_amounts = {!! json_encode($income_paid_amount ?? [], JSON_HEX_TAG) !!};
        new Chart(e, {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Income",
                    data: amounts,
                    backgroundColor: "#3E97FF"
                }, {
                    label: "Paid",
                    data: paid_amounts,
                    backgroundColor: "#E1E3EA"
                }]
            },
            options: {
                responsive: !0,
                barPercentage: .75,
                categoryPercentage: .5,
                maintainAspectRatio: !1,
                scales: {
                    y: {
                        grid: {
                            display: !1
                        },
                        border: {
                            display: !1
                        },
                        ticks: {
                            stepSize: 15,
                            color: "#6c757d",
                            font: {
                                family: "Inter, sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: !1
                        },
                        border: {
                            display: !1
                        },
                        ticks: {
                            color: "#6c757d",
                            font: {
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                },
                elements: {
                    bar: {
                        borderRadius: 3
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: !0,
                            font: {
                                color: "#6c757d",
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                }
            }
        });
    }

    function initMultipleBarAdminRegistrations() {
        var e = document.getElementById("multipleBarAdminRegistrations");
        var months = {!! json_encode($months ?? [], JSON_HEX_TAG) !!};
        var amounts = {!! json_encode($ir_counts ?? [], JSON_HEX_TAG) !!};
        var paid = {!! json_encode($ir_paid ?? [], JSON_HEX_TAG) !!};
        new Chart(e, {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Requests",
                    data: amounts,
                    backgroundColor: "#3E97FF"
                }, {
                    label: "Paid",
                    data: paid,
                    backgroundColor: "#E1E3EA"
                }]
            },
            options: {
                responsive: !0,
                barPercentage: .75,
                categoryPercentage: .5,
                maintainAspectRatio: !1,
                scales: {
                    y: {
                        grid: {
                            display: !1
                        },
                        border: {
                            display: !1
                        },
                        ticks: {
                            stepSize: 15,
                            color: "#6c757d",
                            font: {
                                family: "Inter, sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: !1
                        },
                        border: {
                            display: !1
                        },
                        ticks: {
                            color: "#6c757d",
                            font: {
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                },
                elements: {
                    bar: {
                        borderRadius: 3
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: !0,
                            font: {
                                color: "#6c757d",
                                family: "Inter, sans-serif"
                            }
                        }
                    }
                }
            }
        });
    }
</script>
