<div>
    <!--! Start:: Breadcumb !-->
    <livewire:comp.breadcumb title="Sales Chart" section="User" sub="My Team" action="Sales Chart">
        <!--! End:: Breadcumb !-->
        <div class="edash-content-section row g-3 g-md-4">

            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <!--  -->
                        <form action="#" class="form-group" id="repeaterAdvanced">
                            <div data-repeater-list="repeater-advanced">
                                <div data-repeater-item>
                                    <div class="form-group row mb-2">
                                        <div class="col-lg-12 mb-2 mb-md-0 pt-1">
                                            <h4 class="card-title mb-2">{{ $selectedUser->name }} /
                                                {{ $selectedUser->id }}
                                            </h4>

                                        </div>
                                        <div class="col-lg-12 mb-2 mb-md-0 pt-1">

                                            <h4 class="card-title align-items-center d-flex justify-content-center">A 1
                                                -
                                                {{ $selectedUser->left_points }}<small class="mr-5 ml-5"> Available
                                                    Points
                                                </small>A 2 -
                                                {{ $selectedUser->right_points }}</h4>
                                        </div>
                                        <div class="col-lg-12 mb-2 mb-md-0 pt-1">

                                            <select class="form-select user-list " wire:model='selectedAccountId'
                                                wire:change='filter()'>
                                                <option value="null">{{ $selectedUser->name }}</option>
                                                @foreach ($myMembers as $member)
                                                    <option value="{{ $member->id }}">{{ $member->id }} -
                                                        {{ $member->first_name . ' ' . $member->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- End:: Filter -->
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

                        <div class="card-header is-sales-chart-title">
                            <h4 class="card-title">Agent 1 /
                                {{ $this->leftAccounts->count() }}</h4>
                        </div>
                        <div class="card-header is-sales-chart-title">
                            <h4 class="card-title">Agent 2 /
                                {{ $this->rightAccounts->count() }}</h4>
                        </div>

                    </div>
                    <div class="card-table table-responsive d-flex ">
                        <div class="table-responsive  col-6 border-right-sales-chart ">
                            <table class="table mb-0">
                                <thead class="border-top">
                                    <tr>
                                        <th scope="col-3">Agent 1</th>
                                        <th scope="col-3">Status</th>
                                        <th scope="col-6">Name</th>
                                        <th scope="col-3">Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leftAccounts as $leftAc)
                                        <tr>
                                            <td><a href="javascript:void(0)">
                                                    @if ($leftAc->er_status == '2')
                                                        <h6 class="half-color">{{ $leftAc->id }}</h6>
                                                    @elseif ($leftAc->er_status == '3')
                                                        <h6 class="full-color">{{ $leftAc->id }}</h6>
                                                    @elseif ($leftAc->er_status == '4')
                                                        <h6 class="er-color">{{ $leftAc->id }}</h6>
                                                    @endif
                                                </a></td>
                                            <td>
                                                @if ($leftAc->er_status == '2')
                                                    <h6 class="half-color">HALF</h6>
                                                @elseif ($leftAc->er_status == '3')
                                                    <h6 class="full-color">FULL</h6>
                                                @elseif ($leftAc->er_status == '4')
                                                    <h6 class="er-color">ER</h6>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="d-flex gap-3 align-items-center">

                                                    <div class="flex-shrink-0">
                                                        <div>{{ $leftAc->first_name . ' ' . $leftAc->last_name }}</div>

                                                    </div>
                                                </a>
                                            </td>

                                            <td>
                                                <h6 class="point-color"> A1 :{{ $leftAc->left_points }}
                                                    A2 :{{ $leftAc->right_points }}</h6>

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive col-6 ">
                            <table class="table mb-0">
                                <thead class="border-top">
                                    <tr>
                                        <th scope="col-3">Agent 2</th>
                                        <th scope="col-3">Status</th>
                                        <th scope="col-6">Name</th>
                                        <th scope="col-3">Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rightAccounts as $rightAc)
                                        <tr>
                                            <td>
                                                @if ($rightAc->er_status == '2')
                                                    <h6 class="half-color">{{ $rightAc->id }}</h6>
                                                @elseif ($rightAc->er_status == '3')
                                                    <h6 class="full-color">{{ $rightAc->id }}</h6>
                                                @elseif ($rightAc->er_status == '4')
                                                    <h6 class="er-color">{{ $rightAc->id }}</h6>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($rightAc->er_status == '2')
                                                    <h6 class="half-color">HALF</h6>
                                                @elseif ($rightAc->er_status == '3')
                                                    <h6 class="full-color">FULL</h6>
                                                @elseif ($rightAc->er_status == '4')
                                                    <h6 class="er-color">ER</h6>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="d-flex gap-3 align-items-center">

                                                    <div class="flex-shrink-0">
                                                        <div>{{ $rightAc->first_name . ' ' . $rightAc->last_name }}
                                                        </div>

                                                    </div>
                                                </a>
                                            </td>

                                            <td>
                                                <h6 class="point-color"> A1 : {{ $rightAc->left_points }} A2 :
                                                    {{ $rightAc->right_points }}
                                                </h6>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: Zero Config -->
        </div>

        <style>
            .user-list {
                max-width: 317px;
                margin: 20px auto 0 auto;
            }

            .form-group {
                text-align: center;

            }

            small {
                font-size: 12px;
                margin: 0 20px;
            }

            .card-header.is-sales-chart-title {
                width: 50%;
                text-align: center;
            }

            .er-color {
                color: green;
            }

            .full-color {
                color: rgb(23, 136, 211);
            }

            .half-color {
                color: rgb(23, 36, 211);
            }

            .point-color {
                color: red !important;
            }

            .table-responsive.col-6.border-right-sales-chart {
                border-right: 5px solid var(--expo-border-color);
            }
        </style>
</div>
