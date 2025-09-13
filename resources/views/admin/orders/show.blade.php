@extends('layouts.app')

@section('title','Order Details')

@section('contents')


    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Order Details</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Order Details</li>
            </ul>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    @php
                        $vendorName = optional($order->vendor)?->name;


                    @endphp

                    <div class="card-header bg-success text-light">
                        Vendor : {{ $vendorName ? ucfirst($vendorName) : 'N/A' }}
                        Order Taken By  :  {{ $buyer_details->name ? ucfirst($buyer_details->name) : 'N/A' }}
                    </div>
                    <div class="card-header bg-primary text-light">Order Details</div>
                    

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Status</label>
                                    <div class="col-sm-4">
                                    @if($order->status == 1)
                                        <label class="btn btn-success btn-sm waves-effect">{{ ucfirst($order->order_status) }}</label>
                                    @elseif($order->is_cancel == 1)
                                        <label class="btn btn-danger btn-sm waves-effect">{{ ucfirst($order->order_status) }}</label>
                                    @else
                                        <label class="btn btn-secondary btn-sm waves-effect">{{ ucfirst($order->order_status) }}</label>
                                        @if($order->order_status == 'Order Placed' || $order->order_status == 'Order Confirmed' || $order->order_status == 'Preparing' || $order->order_status == 'Ready for Pickup') {{-- || ($order->order_type == 'takeaway' && $order->order_status == 'Ready for Pickup') --}}
                                        <a href="#" class="btn btn-primary" data-bs-placement="top"  title="Edit this Item" data-bs-toggle="modal" data-bs-target="#updateStatusModal_<?= $order->id; ?>"><i class="fa fa-edit option-icon"></i>Update order Status</a>
                                        @endif

                                    @endif
                                    </div>
                                </div>
                                @if(!empty($delivery_partner))
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Delivery Partner</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $delivery_partner[0]->name }} ({{$delivery_partner[0]->mobile_no}}) </strong>
                                    </div>
                                </div>
                                @endif
                                @if($order->is_cancel == 1)
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Cancel Reason</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->cancel_cause }}</strong>
                                    </div>
                                </div>
                                @endif
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Order Number</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->order_number }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Order Type</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right" id="order_type">{{ ucfirst($order->order_type) }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Payment Method</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">
                                            {{ ucfirst($order->payment_method) }}
                                        </strong>
                                    </div>
                                </div>
                                {{-- <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Currency</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->price_currency }}</strong>
                                    </div>
                                </div> --}}
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Payment Status</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ ucfirst($order->payment_status) }}</strong>
                                        @if($order->payment_status == 'Awaiting Payment' && $order->order_status != 'Cancelled' && $order->order_status != 'Rejected' && $order->payment_method == 'Cash On Delevery')
                                        <a href="#" class="btn btn-primary" data-bs-placement="top"  title="Edit this Item" data-bs-toggle="modal" data-bs-target="#updatePaymentStatusModal_<?= $order->id; ?>"><i class="fa fa-edit option-icon"></i>Update Payment Status</a>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Payment Date</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->payment_date ? format_datetime($order->payment_date) : '' }}</strong>
                                    </div>
                                </div> --}}
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Order Taken</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $buyer_details->name }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Phone Number</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $buyer_details->phone }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Email</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $buyer_details->email }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Address</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->formatted_address }}</strong>
                                    </div>
                                </div>

                                {{-- <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Landmark</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->landmark }}</strong>
                                    </div>
                                </div> --}}
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Contact Number</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->contact_number }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="example-text-input" class="col-sm-4 col-form-label">Order Note</label>
                                    <div class="col-sm-8">
                                        <strong class="font-right">{{ $order->delevery_note }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">Order Items</div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    {{-- <th>Product Id</th> --}}
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Gst</th>
                                    <th>Total</th>
                                    <!-- <th class="max-width-120">Options</th> -->
                                </tr>
                            </thead>

                            <tbody>
                                @php $subtotal = 0; @endphp
                                @php $gst = 0; @endphp
                                @php $shipping = 0; @endphp
                                @foreach ($order_items as $item)
                                <tr>
                                    {{-- <td>{{ $item->product_id }}</td> --}}
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ 0.00 }}</td>
                                    @php $subtotal += $item->subtotal @endphp
                                    <td>{{ $item->subtotal }}</td>
                                    <!-- <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Options <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="#" class="dropdown-item" data-bs-placement="top"  title="Edit this Item" data-bs-toggle="modal" data-bs-target="#updateStatusModal_<?= $item->id; ?>"><i class="fa fa-edit option-icon"></i>Update order Status</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove this Item"> <i class="fas fa-trash-alt text-danger" title="Remove"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-lg-4 float-end">
                            <div class="row mb-0">
                                <label for="example-text-input" class="col-sm-4 col-form-label float-end">Subtotal</label>
                                <div class="col-sm-8"><strong class="float-end">{{ $subtotal }}</strong></div>
                            </div>
                            <div class="row mb-0">
                                <label for="example-text-input" class="col-sm-4 col-form-label float-end">GST</label>
                                <div class="col-sm-8"><strong class="float-end">{{ $gst }}</strong></div>
                            </div>
                            <div class="row mb-0">
                                <label for="example-text-input" class="col-sm-4 col-form-label float-end">Shipping</label>
                                <div class="col-sm-8"><strong class="float-end">{{ $shipping }}</strong></div>
                            </div>
                            <div class="row mb-0">
                                <label for="example-text-input" class="col-sm-4 col-form-label float-end">Coupon Discount</label>
                                <div class="col-sm-8"><strong class="float-end">{{ $order->coupone_discount }} @if($order->coupone_discount > 0) ( Coupon Code - {{ $order->coupone_code }} ) @endif</strong></div>
                            </div>
                            <div class="row mb-0">
                                <label for="example-text-input" class="col-sm-4 col-form-label float-end">Total</label>
                                <div class="col-sm-8"><strong class="float-end">{{ $order->total_amount }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    {{-- @foreach ($order_items as $item) --}}
<div class="modal fade" id="updateStatusModal_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('order.update-order-status') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-order-status">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select name="order_status" class="form-select" aria-label="Default select example" id="orderOption" onchange="toggleFields()">
                                <option value="Order Placed" <?php echo ($order->order_status == 'Order Placed') ? 'selected' : ''; ?>>Order Placed</option>
                                <option value="Order Confirmed" <?php echo ($order->order_status == 'Order Confirmed') ? 'selected' : ''; ?>>Order Confirmed</option>
                                <option value="Preparing" <?php echo ($order->order_status == 'Preparing') ? 'selected' : ''; ?>>Preparing</option>
                                <option value="Ready for Pickup" <?php echo ($order->order_status == 'Ready for Pickup') ? 'selected' : ''; ?>>Ready for Pickup</option>
                                {{-- @if($order->order_type == 'takeaway') --}}
                                <option value="Delivered" <?php echo ($order->order_status == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                {{-- @endif --}}
                                <option value="Rejected" <?php echo ($order->order_status == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <div id="cancellCauseField" class="d-none mt-3">
                                <label class="control-label" for="trackingNumber">Explain the reasons for the cancellation</label>
                                <textarea type="text" class="form-control mb-3" id="trackingNumber" name="cancel_cause"></textarea>
                            </div>
                            <div id="shippedFields" class="d-none mt-3">
                                <label class="control-label" for="trackingNumber">Choose Delivery Partner</label>
                                <!-- <input type="text" class="form-control mb-3" id="trackingNumber" name="shipping_tracking_number"> -->
                                <select name="delivary_partner" class="from-control form-select mt-2" id="delivary_partner">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- @endforeach --}}
<div class="modal fade" id="updatePaymentStatusModal_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('order.update-payment-status') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Update Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-order-status">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select name="order_status" class="form-select" aria-label="Default select example" id="shippingOption" onchange="toggleFields()">
                                <option value="Awaiting Payment" <?php echo ($order->payment_status == 'Awaiting Payment') ? 'selected' : ''; ?>>Awaiting Payment</option>
                                <option value="Payment Received" <?php echo ($order->payment_status == 'Payment Received') ? 'selected' : ''; ?>>Payment Received</option>
                            </select>
                        </div>
                    </div>
                </div>   
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    let table = new DataTable("#dataTable");
</script>
<script>
	const popupCenter = ({url, title, w, h}) => {
        // Fixes dual-screen position                             Most browsers      Firefox
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;
        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title, 
        `
        scrollbars=yes,
        width=${w / systemZoom}, 
        height=${h / systemZoom}, 
        top=${top}, 
        left=${left}
        `
        )
        if (window.focus) newWindow.focus();
        newWindow.print();
    }
</script>


@endsection