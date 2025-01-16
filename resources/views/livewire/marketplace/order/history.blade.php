<div>
    <!--! Start:: Breadcumb !-->
    <livewire:comp.breadcumb title="MARKETPLACE " section="Order" sub="History" action="All">
        <!--! End:: Breadcumb !-->
        <div class="edash-content-section row g-3 g-md-4">

            <!-- Start:: Zero Config -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">All Orders</h4>
                    </div>
                    <div class="card-table table-responsive">
                        <table id="zeroConfig" class="table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order Number</th>
                                    <th>Total Amount ( Rs )</th>
                                    <th>Payment Method</th>
                                    <th>Ordered At</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order?->id }}</td>
                                        <td>{{ $order?->order_number }}</td>
                                        <td>{{ $order?->total_amount }}</td>
                                        <td>
                                            @if ($order?->payment_method == 1)
                                                <span class="badge bg-warning ms-1 rounded-pill">WALLET</span>
                                            @elseif($order?->payment_method == 2)
                                                <span class="badge bg-primary ms-1 rounded-pill">COD</span>
                                            @else
                                            @endif
                                        </td>
                                        <td>{{ $order?->created_at }}</td>
                                        <td>
                                            @if ($order?->status == 0)
                                                <span class="badge bg-warning ms-1 rounded-pill">Pending</span>
                                            @elseif($order?->status == 1)
                                                <span class="badge bg-primary ms-1 rounded-pill">Inprogress</span>
                                            @elseif($order?->status == 2)
                                                <span class="badge bg-primary ms-1 rounded-pill">Delivered</span>
                                            @elseif($order?->status == 3)
                                                <span class="badge bg-primary ms-1 rounded-pill">Completed</span>
                                            @elseif($order?->status == 4)
                                                <span class="badge bg-primary ms-1 rounded-pill">Cancelled</span>
                                            @else
                                            @endif
                                        </td>


                                        {{-- <td>
                                            <button class="btn btn-primary" wire:click='approve({{ $course?->id }})'
                                                type="button">
                                                APPROVE
                                            </button>
                                            @if ($course?->status != 2)
                                                <button class="btn btn-danger" wire:click='cansel({{ $course?->id }})'
                                                    type="button">
                                                    CANSEL
                                                </button>
                                            @endif
                                        </td> --}}

                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Order Number</th>
                                    <th>Total Amount ( Rs )</th>
                                    <th>Payment Method</th>
                                    <th>Ordered At</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End:: Zero Config -->

        </div>
</div>
