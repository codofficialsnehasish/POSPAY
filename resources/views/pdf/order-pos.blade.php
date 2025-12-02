<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Receipt</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            width: 280px; /* 58mm POS width */
            margin: 0 auto;
            font-size: 12px;
        }

        .center { text-align: center; }
        .bold { font-weight: bold; }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
        }

        .item-row {
            display: flex;
            margin: 3px 0;
        }

        .item-name { width: 43%; }
        .item-qty  { width: 15%; text-align: center; }
        .item-rate { width: 21%; text-align: center; }
        .item-amt  { width: 21%; text-align: right; }

        .dashed {
            border-top: 1px dashed #000;
            margin: 6px 0;
            padding-top: 4px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        img.logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .vendor-info {
            margin-left: 10px;
            font-size: 12px;
            line-height: 15px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div style="display:flex; align-items:center; margin-top:10px;">

        <div style="width:60px;">
            <img src="{{ $branch->getFirstMediaUrl('user-image') }}" class="logo" />
        </div>

        <div class="vendor-info">
            <div class="bold">{{ $branch->store_number }} - {{ $branch->name }}</div>
            <div>{{ $branch->store_location }}</div>
            <div>{{ $branch->phone }}</div>
            <div>{{ $branch->email }}</div>
            <div>GST : {{ $branch->gst_no ?? '--' }}</div>
        </div>
    </div>

    <div class="dashed"></div>

    <!-- Customer Info -->
    <div>
        <div class="row">
            <span>Customer No.</span>
            <span>{{ $order->contact_number ?? '--' }}</span>
        </div>

        <div class="row">
            <span>Mode</span>
            <span>{{ $order->payment_method ?? '--' }}</span>
        </div>

        <div class="row">
            <span>Seat No.</span>
            <span>{{ $order->seat_number ?? '--' }}</span>
        </div>
    </div>

    <div class="dashed"></div>

    <!-- Bill No & Date -->
    <div class="row">
        <span>Bill : {{ $order->order_number }}</span>
        <span>Date : {{ $order->created_at->format('d/m/y, H:i') }}</span>
    </div>

    <div class="dashed"></div>

    <!-- Table Header -->
    <div class="item-row bold" style="font-size: 13px;">
        <div class="item-name">Item</div>
        <div class="item-qty">Qty</div>
        <div class="item-rate">Rate</div>
        <div class="item-amt">Amt</div>
    </div>

    <!-- Items -->
    @foreach($order->items as $item)
    <div class="item-row">
        <div class="item-name">{{ $item->product->name }}</div>
        <div class="item-qty">{{ $item->quantity }}</div>
        <div class="item-rate">{{ $item->mrp }}</div>
        <div class="item-amt">₹{{ $item->subtotal }}</div>
    </div>
    @endforeach

    <div class="dashed"></div>

    <!-- Totals -->
    <div class="total-row bold">
        <span>Sub Total</span>
        <span>₹{{ $order->price_subtotal }}</span>
    </div>

    <div class="total-row">
        <span>Discount</span>
        <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
    </div>

    <div class="total-row">
        <span>Complimentary</span>
        <span>- ₹{{ number_format($order->complimentary_amount, 2) }}</span>
    </div>

    <div class="total-row">
        <span>CGST</span>
        <span>₹{{ number_format($order->cgst_amount, 2) }}</span>
    </div>

    <div class="total-row">
        <span>SGST</span>
        <span>₹{{ number_format($order->sgst_amount, 2) }}</span>
    </div>

    <div class="dashed"></div>

    <div class="total-row bold" style="font-size: 14px;">
        <span>Grand Total</span>
        <span>₹{{ $order->grand_total }}</span>
    </div>

    <div class="center" style="margin-top:10px;">
        ****** Thank You Visit Again ******
    </div>

</body>
</html>
