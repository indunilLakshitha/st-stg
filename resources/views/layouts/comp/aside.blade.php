<!--! ================================================================ !-->
<!--! Start:: Main Menu !-->
<!--! ================================================================ !-->
<aside class="edash-menu position-fixed z-1030 start-0 top-0 end-0 bottom-0 bg-body-tertiary border-end" id="edash-menu">
    <!-- Start:: Logo -->
    <div class="edash-menu-header ht-80 d-flex align-items-center px-5 py-4 position-relative">
        <a href="{{ route('admin.dashboard') }}" class="edash-logo">
            <img src="{{ asset('assets/images/logo-main.png') }}" alt="logo" class="img-fluid edash-logo-main" />
            <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="logo" class="img-fluid edash-logo-abbr" />
        </a>
    </div>
    <!-- End:: Logo -->
    <!-- Start::  Sidebar Nav -->
    <nav class="edash-sidebar-nav position-relative z-2" id="edash-sidebar-nav" style="height: calc(100vh - 5rem)">
        @php
            $user = Auth::user();
            $status = $user->er_status;
        @endphp
        @if (!Auth::user()->is_admin)
            <ul class="edash-metismenu" id="edash-metismenu">
                <li class="nav-label mb-2 mt-4 px-5 fs-11 fw-semibold text-muted text-uppercase"
                    style="letter-spacing: 1px">
                    Navigations
                </li>
                @if (in_array($status, [2, 3, 4]))
                    <li>
                        <a class="" href="{{ route('admin.dashboard') }}">
                            <i class="fi fi-rr-airplay"></i>
                            <span class="mm-text">Dashboard</span>
                        </a>

                    </li>
                @endif
                <li class="nav-label mb-2 mt-4 px-5 fs-11 fw-semibold text-muted text-uppercase"
                    style="letter-spacing: 1px">
                    Webapps
                </li>

                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-layers"></i>
                        <span class="mm-text">Courses</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('course.index') }}" class="sub-menu">All</a>
                        </li>
                        <li><a href="{{ route('course.myCourses') }}" class="sub-menu">My Courses</a></li>

                    </ul>
                </li>

                @if (in_array($status, [4]))
                    <li>
                        <a href="{{ route('wallet.index') }}">
                            <i class="fi fi-rr-envelope-dot"></i>
                            <span class="mm-text">Wallet</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('marketplace.user.index') }}">
                        <i class="fi fi-rr-shopping-cart"></i>
                        <span class="mm-text">Marketplace</span>
                    </a>
                </li>

                @if (in_array($status, [4]))
                    <li>
                        <a class="has-arrow" href="javascript:void(0);">
                            <i class="fi fi-rr-users-alt"></i>
                            <span class="mm-text">My Team</span>
                        </a>
                        <ul>
                            @if ($user->is_tree_enabled)
                                <li>
                                    <a href="{{ route('my.tree') }}" class="sub-menu">Tree View</a>
                                </li>
                            @endif
                            <li><a href="{{ route('my.sales') }}" class="sub-menu">Sales Chart</a></li>
                            <li><a href="{{ route('my.team') }}" class="sub-menu">Direct Team</a></li>
                            <li><a href="{{ route('my.pendingActivations') }}" class="sub-menu">Pending Activation </a>
                            </li>
                            <li><a href="{{ route('referrals.pending') }}" class="sub-menu">Not Approved</a></li>

                        </ul>
                    </li>
                @endif
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi  fi-rr-file-invoice"></i>
                        <span class="mm-text">Reports</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('report.index') }}" class="sub-menu">Income Reports</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-bags-shopping"></i>
                        <span class="mm-text">Orders</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('order.history') }}" class="sub-menu">Order History</a>
                        </li>
                        <li>
                            <a href="{{ route('order.marketHistory') }}" class="sub-menu">Marketplace Order
                                History</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('profile.index') }}">
                        <i class="fi fi-rr-comment-alt-dots"></i>
                        <span class="mm-text">Profile</span>
                    </a>
                </li>

                {{-- <li class="nav-label mb-2 mt-4 px-5 fs-11 fw-semibold text-muted text-uppercase"
                    style="letter-spacing: 1px">
                    Action
                </li> --}}


            </ul>
        @else
            <ul class="edash-metismenu" id="edash-metismenu">
                <li class="nav-label mb-2 mt-4 px-5 fs-11 fw-semibold text-muted text-uppercase"
                    style="letter-spacing: 1px">
                    Navigations
                </li>
                <li>
                    <a class="" href="{{ route('admin.dashboard') }}">
                        <i class="fi fi-rr-airplay"></i>
                        <span class="mm-text">Dashboard</span>
                    </a>

                </li>
                <li class="nav-label mb-2 mt-4 px-5 fs-11 fw-semibold text-muted text-uppercase"
                    style="letter-spacing: 1px">
                    Webapps
                </li>

                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-users-alt"></i>
                        <span class="mm-text">Customers</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.customer.index') }}" class="sub-menu">Customers</a>
                        </li>
                        <li><a href="{{ route('admin.team.index') }}" class="sub-menu">Team View</a></li>
                        <li>
                            <a href="{{ route('admin.customer.requests') }}" class="sub-menu">Pending Activations</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.customer.registrationRequests') }}" class="sub-menu">Registration
                                Requests</a>
                        </li>
                        <li>
                            <a href="{{ route('my.tree') }}" class="sub-menu">Tree View</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi  fi-rr-dollar"></i>
                        <span class="mm-text">Commisions</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.commission.index') }}" class="sub-menu">Commisions List</a>
                        </li>
                        <li><a href="{{ route('admin.commission.generate') }}" class="sub-menu">Generate
                                Commisions</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-bank"></i>
                        <span class="mm-text">Payments</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.sales.index') }}" class="sub-menu">Sales Report</a>
                        </li>
                        <li><a href="{{ route('admin.deposited.pending') }}" class="sub-menu">Withdrawal Requests
                            </a></li>
                        <li><a href="{{ route('admin.deposited.index') }}" class="sub-menu">Cash Deposited List</a>
                        </li>
                        <li><a href="{{ route('admin.order.history') }}" class="sub-menu">Orders</a></li>

                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-layers"></i>
                        <span class="mm-text">Courses</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.course.category.index') }}" class="sub-menu">Category</a>
                        </li>
                        <li><a href="{{ route('admin.course.index') }}" class="sub-menu">Courses</a></li>
                        {{-- <li><a href="#" class="sub-menu">Cash Deposited List</a></li --}}

                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-ss-user"></i>
                        <span class="mm-text">Admin</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.user.index') }}" class="sub-menu">Admin</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.pasword.list') }}" class="sub-menu">Password Manager</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.settings') }}" class="sub-menu">Settings</a>
                        </li>
                        <li>
                            <a href="{{ env('APP_URL_ADMIN') }}" class="sub-menu">Market</a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="nav-label mb-2 mt-4 px-5 fs-11 fw-semibold text-muted text-uppercase"
                    style="letter-spacing: 1px">
                    Marketplace
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-layers"></i>
                        <span class="mm-text">Product</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('marketplace.admin.category.index') }}" class="sub-menu">Category</a>
                        </li>
                        <li><a href="{{ route('marketplace.admin.product.index') }}" class="sub-menu">Product</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="{{ route('wallet.index') }}">
                        <i class="fi fi-rr-envelope-dot"></i>
                        <span class="mm-text">Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="./app-invoice.html">
                        <i class="fi fi-rr-file-invoice"></i>
                        <span class="mm-text">Marketplace</span>
                    </a>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0);">
                        <i class="fi fi-rr-layers"></i>
                        <span class="mm-text">My Team</span>
                    </a>
                    <ul>
                        @if ($user->is_tree_enabled)
                            <li>
                                <a href="{{ route('my.tree') }}" class="sub-menu">Tree View</a>
                            </li>
                        @endif
                        <li><a href="./base-avatar.html" class="sub-menu">Sales Chart</a></li>
                        <li><a href="{{ route('my.team') }}" class="sub-menu">Direct Team</a></li>
                        <li><a href="{{ route('referrals.pending') }}" class="sub-menu">Not Approved</a></li>

                    </ul>
                </li>
            </ul> --}}

        @endif
        {{-- <li>
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf

                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </li>
        <div class="card text-center mx-3 mt-6 mb-4 bg-primary-subtle">
            <div class="card-body vstack gap-4">
                <i class="fi fi-rr-rocket-lunch fs-1 text-primary" style="transform: rotate(320deg)"></i>
                <div>
                    <h5 class="mb-2 text-primary">Help Center</h5>
                    <p class="fs-13 fw-light mb-0 text-primary">
                        Expodash is a production ready multi dashboard to get started up and
                        running easily.
                    </p>
                </div>
                <a href="mailto:wrapcoders@gmail.com" class="btn btn-primary text-white w-100">Get Support</a>
            </div>
        </div> --}}
    </nav>
    <!-- End:: Sidebar Nav -->
</aside>
