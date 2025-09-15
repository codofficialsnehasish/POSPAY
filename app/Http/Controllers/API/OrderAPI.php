<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Cart;
use App\Models\ProductVariationOption;
use App\Models\OrderSeat;
use App\Models\Product;
use App\Models\Coach;
use App\Models\User;
use App\Models\SeatNumber;
use App\Models\Transaction;
use Razorpay\Api\Api;

class OrderAPI extends Controller
{
    public function createRazorpayOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1', // amount in INR
            'currency' => 'nullable|string|in:INR'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt'         => 'rcptid_' . uniqid(),
            'amount'          => $request->amount * 100, // Razorpay works in paise
            'currency'        => $request->currency ?? 'INR',
            'payment_capture' => 1 // auto capture
        ];

        try {
            $razorpayOrder = $api->order->create($orderData);

            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency'],
                'receipt' => $razorpayOrder['receipt'],
                'key' => env('RAZORPAY_KEY') // frontend will need this
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function place_order(Request $request){
        
        $validator = Validator::make($request->all(), [
            'contact_number' => 'nullable',
            'contact_purson' => 'nullable',
            'delevery_note' => 'nullable',
            'payment_method' => 'nullable',
            'gateway_transaction_id'=> 'nullable|string',
            'razorpay_order_id'     => 'nullable|string',
            'razorpay_signature'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $cart_items = Cart::where('user_id', $request->user()->id)->get();
        $totalGst =0;
        if($cart_items){

            $cart_sub_total = calculate_cart_sub_total_by_userId($request->user()->id);
            $cart_total = calculate_cart_total_by_userId($request->user()->id);
            // $coupone_discount = !empty($request->coupone_code) ? get_coupone_discount($request->coupone_code,$cart_total) : 0.00;

            // 🔹 If UPI → Verify Razorpay payment
            if ($request->payment_method === "UPI") {
                if (!$request->razorpay_order_id || !$request->gateway_transaction_id || !$request->razorpay_signature) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Missing Razorpay payment details'
                    ], 422);
                }

                try {
                    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

                    $attributes = [
                        'razorpay_order_id'   => $request->razorpay_order_id,
                        'razorpay_payment_id' => $request->gateway_transaction_id,
                        'razorpay_signature'  => $request->razorpay_signature,
                    ];

                    $api->utility->verifyPaymentSignature($attributes);
                    $payment_status = "Payment Received"; // ✅ verified
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Payment verification failed: '.$e->getMessage()
                    ], 400);
                }
            } else {
                // COD / Online(Card without Razorpay integration here)
                $payment_status = ($request->payment_method == 'Cash')
                    ? 'Awaiting Payment'
                    : 'Payment Received';
            }

            // return $payment_status;
    
            $order= Order::create([
                'order_number'=>generateOrderNumber(),
                'user_id'=>$request->user()->id,
                'vendor_id'=>$request->user()->vendor_id,
                'order_type'=>"attendee",
                'order_status'=>"Order Confirmed",
                'price_subtotal'=>$cart_sub_total,
                'price_gst'=>0.00,
                'total_amount'=>calculate_cart_total_by_userId($request->user()->id),
                'discounted_price'=>calculate_cart_total_by_userId($request->user()->id),
                'payment_method'=>$request->payment_method,
                // 'payment_status'=>$request->payment_method == 'Online' || $request->payment_method == 'UPI'|| $request->payment_method == 'Card' ? 'Payment Received':'Awaiting Payment',
                'payment_status'=>$payment_status,
                'contact_number'=>$request->contact_number,
                'discount_amount'=>$request->discount_amount,
                'complimentary_amount'=>$request->complimentary_amount,
            ]);
            
            $totalDiscount= $request->discount_amount + $request->complimentary_amount;
            $discounted_price =  $order->discounted_price - $totalDiscount;
            $order->discounted_price = $discounted_price;
            $order->save();
            
            
            update_order_number($order->id, $order->order_number);

            foreach($cart_items as $cart_item){
                
                
                if ($cart_item->option_id) {
                    $price = get_product_price($cart_item->product_id, $cart_item->option_id);
                    
                    OrderItems::create([
                        'order_id'=>$order->id,
                        'product_id'=>$cart_item->product_id,
                        'variation_id'=>$cart_item->variation_id,
                        'option_id'=>$cart_item->option_id,
                        'product_name'=>$cart_item->product_title,
                        'quantity'=>$cart_item->quantity,
                        'price'=>$cart_item->options->price,
                        'mrp'=>$cart_item->options->mrp,
                        'discount_rate'=>$cart_item->options->discount_rate,
                        'discount_amount'=>$cart_item->options->discount_amount,
                        'subtotal'=>$cart_item->options->price * $cart_item->quantity,
                    ]);
                }else{
                    $price = get_product_price($cart_item->product_id, $cart_item->option_id);
                    OrderItems::create([
                        'order_id'=>$order->id,
                        'product_id'=>$cart_item->product_id,
                        'product_name'=>$cart_item->product_title,
                        'quantity'=>$cart_item->quantity,
                        'price'=>$price,
                        'mrp'=>$cart_item->product->price,
                        'discount_rate'=>$cart_item->product->discount_rate,
                        'discount_amount'=>$cart_item->product->discount_price,
                        'subtotal'=>$price * $cart_item->quantity,
                    ]);
                }
                
                $totalGst += $cart_item->product->gst_amount ;

                
            }
            

            $cgst = $totalGst / 2;
            $sgst = $totalGst / 2;
            
            $order->gst_amount = $totalGst;
            $order->cgst_amount = $cgst;
            $order->sgst_amount = $sgst;

            $order->save();
            
            if ($request->has('seat_number') && is_array($request->seat_number)) {
                foreach ($request->seat_number as $seat) {
                    OrderSeat::create([
                        'order_id' => $order->id,
                        'seat_number' => $seat,
                    ]);
                }
            }


            // 🔑 Save Transaction
            Transaction::create([
                'order_id'                => $order->id,
                'user_id'                 => $request->user()->id,
                'vendor_id'               => $request->user()->vendor_id,
                'transaction_number'      => generateTransactionNumber(), // you can write a helper
                'amount'                  => $order->discounted_price,
                'payment_method'          => $request->payment_method,
                'payment_status'          => $payment_status ?? 'Awaiting Payment',
                'gateway_transaction_id'  => $request->gateway_transaction_id ?? null,
                'currency'                => 'INR',
                'paid_at'                 => $payment_status == 'Payment Received' ? now() : null,
            ]);

            $cart_items = Cart::where('user_id', $request->user()->id)->delete(); 
            $seats = OrderSeat::where('order_id', $order->id)->pluck('seat_number');
            
            
            $order->load([
                'items.product.media',  
                'seats'
            ]);
            
             $user = $request->user();
            
            $user->load(['vendor.branch.coach']);


            return response()->json([
                'status' => 'true',
                'message' => 'Order Created Successfully',
                'data' => $order,
                'seats'=>$seats
            ], 200);
        }else{
            return response()->json([
                'status' => 'false',
                'message' => 'Empty Cart'
            ], 200);
        }
    }

    // public function order_history(){
    //     $orders = Order::with('items.product.media')->orderBy('id','desc')->get();
    //     return response()->json([
    //         'status' => 'true',
    //         'data' => $orders
    //     ], 200);
    // }
    
    public function order_history(Request $request)
    {
        $query = Order::with('items.product.media')
         ->where('is_darft', 0) 
         ->where('user_id',$request->user()->id)
        ->orderBy('id', 'desc');
    

        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
    
        // if ($request->filled('payment_method')) {
        //     $query->where('payment_method', $request->payment_method);
        // }
    
        // if ($request->filled('vendor_id')) {
        //     $query->where('vendor_id', $request->vendor_id);
        // }
    
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        }
    
        $orders = $query->get();
    

        
        
        return response()->json([
            'response' => true,
            'message' => 'get orders searching data',
                'data' => [
                    'orders' => $orders,
                ],
        ]);
    }
    
    public function place_draft_order(Request $request){
        $validator = Validator::make($request->all(), [
            'contact_number' => 'nullable',
            'contact_purson' => 'nullable',
            'delevery_note' => 'nullable',
            'payment_method' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $totalGst = 0;

        $cart_items = Cart::where('user_id', $request->user()->id)->get();
        if($cart_items){

            $cart_sub_total = calculate_cart_sub_total_by_userId($request->user()->id);
            $cart_total = calculate_cart_total_by_userId($request->user()->id);
            // $coupone_discount = !empty($request->coupone_code) ? get_coupone_discount($request->coupone_code,$cart_total) : 0.00;

    
           $order= Order::create([
                'order_number'=>generateDraftOrderNumber(),
                'user_id'=>$request->user()->id,
                'vendor_id'=>$request->user()->vendor_id,
                'order_type'=>"attendee",
                'order_status'=>"Order Pending",
                'price_subtotal'=>$cart_sub_total,
                'price_gst'=>0.00,
                'total_amount'=>calculate_cart_total_by_userId($request->user()->id),
                'discounted_price'=>calculate_cart_total_by_userId($request->user()->id),
                'payment_method'=>$request->payment_method,
                'payment_status'=>$request->payment_method == 'Online' || $request->payment_method == 'UPI'|| $request->payment_method == 'Card' ? 'Payment Received':'Awaiting Payment',
                'is_darft'=>1,
                'contact_number'=>$request->contact_number,
                'discount_amount'=>$request->discount_amount,
                'complimentary_amount'=>$request->complimentary_amount,
            ]);
            $totalDiscount= $request->discount_amount + $request->complimentary_amount;
            $discounted_price =  $order->discounted_price - $totalDiscount;
            $order->discounted_price =$discounted_price;
            $order->save();
            update_order_number($order->id, $order->order_number);

            foreach($cart_items as $cart_item){


                if ($cart_item->option_id) {
                    $price = get_product_price($cart_item->product_id, $cart_item->option_id);
                    OrderItems::create([
                        'order_id'=>$order->id,
                        'product_id'=>$cart_item->product_id,
                        'variation_id'=>$cart_item->variation_id,
                        'option_id'=>$cart_item->option_id,
                        'product_name'=>$cart_item->product_title,
                        'quantity'=>$cart_item->quantity,
                        'price'=>$price,
                        'mrp'=>$cart_item->options->mrp,
                        'discount_rate'=>$cart_item->options->discount_rate,
                        'discount_amount'=>$cart_item->options->discount_amount,
                        'subtotal'=>$price * $cart_item->quantity,
                    ]);

                }else{
                    $price = get_product_price($cart_item->product_id, $cart_item->option_id);
                    OrderItems::create([
                        'order_id'=>$order->id,
                        'product_id'=>$cart_item->product_id,
                        'product_name'=>$cart_item->product_title,
                        'quantity'=>$cart_item->quantity,
                        'price'=>$price,
                        'mrp'=>$cart_item->product->price,
                        'discount_rate'=>$cart_item->product->discount_rate,
                        'discount_amount'=>$cart_item->product->discount_price,
                        'subtotal'=>$price * $cart_item->quantity,
                    ]);
                }
                $totalGst += $cart_item->product->gst_amount ;
                
            }
            
            $cgst = $totalGst / 2;
            $sgst = $totalGst / 2;
            
            $order->gst_amount = $totalGst;
            $order->cgst_amount = $cgst;
            $order->sgst_amount = $sgst;

            $order->save();
            
            if ($request->has('seat_number') && is_array($request->seat_number)) {
                foreach ($request->seat_number as $seat) {
                    OrderSeat::create([
                        'order_id' => $order->id,
                        'seat_number' => $seat,
                    ]);
                }
            }


            $cart_items = Cart::where('user_id', $request->user()->id)->delete(); 
            $seats = OrderSeat::where('order_id', $order->id)->pluck('seat_number');


            return response()->json([
                'status' => 'true',
                'message' => 'Draft Order Created Successfully',
                'data' => $order,
                'seats'=>$seats
            ], 200);
        }else{
            return response()->json([
                'status' => 'false',
                'message' => 'Empty Cart'
            ], 200);
        }
    }
    
    
    
    public function draft_order_history(Request $request)
    {
        $query = Order::with('items.product.media')
         ->where('user_id',$request->user()->id)
        ->where('is_darft', '1') 
        ->orderBy('id', 'desc');
    
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
    
        // if ($request->filled('payment_method')) {
        //     $query->where('payment_method', $request->payment_method);
        // }
    
        // if ($request->filled('vendor_id')) {
        //     $query->where('vendor_id', $request->vendor_id);
        // }
    
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        }
    
        $orders = $query->get();
    

        
        
        return response()->json([
            'response' => true,
            'message' => 'get orders searching data',
                'data' => [
                    'orders' => $orders,
                ],
        ]);
    }

    
    public function searchOrder(Request $request)
    {
        $query = Order::with('items.product.media')->orderBy('id', 'desc');

        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }


        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        }

        $perPage = $request->input('limit', 10);
        $orders = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }




    // public function order_details($id = null){

    //     $user= User::findOrFail($request->user()->id);
    //     if($id != null){
    //         $order = Order::with('items.product.media')->where('id',$id)->get();
    //         return response()->json([
    //             'status' => 'true',
    //             'data' => $order
    //         ], 200);
    //     }else{
    //         return response()->json([
    //             'status' => 'false',
    //             'maessage' => 'Please provide Order ID'
    //         ], 200);
    //     }

    // }


    public function order_details(Request $request, $id = null)
    {
        $user = $request->user(); 
        
        if ($id != null) {

            $order = Order::with('items.product.media','seats')->where('id', $id)->where('user_id',$request->user()->id)->first();

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found.'
                ], 404);
            }
            $user->load(['vendor.branch.coach']);

            return response()->json([
                'status' => true,
                'order' => $order,
                'user' => $user,
        
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Please provide Order ID'
            ], 400);
        }
    }

    public function cancel_order(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'cause' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order = Order::find($request->order_id);
        $order->order_status = 'Cancelled';
        $order->cancel_cause = $request->cause;
        $res = $order->update();
        if($res){
            return response()->json([
                'status' => 'true',
                'message' => 'Order Cancelled Successfully',
                'data' => $order
            ], 200);
        }else{
            return response()->json([
                'status' => 'false',
                'message' => 'Order Not Cancelled',
                'data' => $order
            ], 200);
        }
    }
    
    public function orderUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'order_id' => 'required|integer|exists:orders,id',
            'items' => 'nullable|array|min:1',
            'items.*.variation_id' => 'nullable|integer|exists:product_variations,id',
            'items.*.option_id' => 'nullable|integer|exists:product_variation_options,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($request->product_id);
        $orderItems = [];
        if (!empty($request->items)) {
            foreach ($request->items as $item) {
                $price = get_product_price($request->product_id, $item['option_id']);
                $option = ProductVariationOption::findOrFail($item['option_id']);
                $product_title = $product->name . '-' . $option->name;
    
                $orderItem = OrderItems::where('order_id', $request->order_id)
                    ->where('product_id', $request->product_id)
                    ->where('variation_id', $item['variation_id'])
                    ->where('option_id', $item['option_id'])
                    ->first();
    
                if ($orderItem) {
                    $orderItem->quantity += $item['quantity'];
                    $orderItem->subtotal = $orderItem->quantity * $price;
                    $orderItem->save();
                } else {
                    $orderItem = OrderItems::create([
                        'order_id' => $request->order_id,
                        'product_id' => $request->product_id,
                        'variation_id' => $item['variation_id'],
                        'option_id' => $item['option_id'],
                        'quantity' => $item['quantity'],
                        'product_name' => $product_title,
                        'price' => $price,
                        'subtotal' => $price * $item['quantity'],
                    ]);
                }
    
                $orderItem->load('product');
                $orderItem->product->image_url = getProductMainImage($orderItem->product_id);
                $orderItems[] = $orderItem;
            }
        }else{
            $price = get_product_price($request->product_id);
            $product_title = $product->name;
            $qty = $request->quantity ?? 1;
            $orderItem = OrderItems::where('order_id', $request->order_id)
                ->where('product_id', $request->product_id)
                ->whereNull('variation_id')
                ->whereNull('option_id')
                ->first();
            if ($orderItem) {
                $orderItem->quantity += $qty;
                $orderItem->subtotal = $orderItem->quantity * $price;
                $orderItem->save();
            } else {
                $orderItem = OrderItems::create([
                    'order_id' => $request->order_id,
                    'product_id' => $request->product_id,
                    'quantity' => $qty,
                    'product_name' => $product_title,
                    'price' => $price,
                    'subtotal' => $price * $qty,
                ]);
            }
            $orderItem->subtotal = $orderItem->quantity * $price;
            $orderItem->save();
            
          
            $orderItem->load('product');
            $orderItem->product->image_url = getProductMainImage($orderItem->product_id);
    
            $orderItems[] = $orderItem;
        }

        // ✅ Recalculate totals after updating all items
        $order = Order::findOrFail($request->order_id);
        $order->price_subtotal = calculate_orderItems_total_by_orderId($order->id);
        $order->total_amount = calculate_orderItems_total_by_orderId($order->id);
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully.',
            'data' => $orderItems,
        ]);
    }
    
    
    public function order_items(Request $request){
        
        $order = Order::find($request->order_id);
        // echo "<pre>";
        // print_r($order);
        // die;
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.'
            ], 404);
        }
        $orderItems =  OrderItems::with('product')->where('order_id', $order->id)->get();
        $orderItems->each(function ($orderItem) {
   
            $orderItem->product->image_url = getProductMainImage($orderItem->product_id);
        });

        return response()->json([
            'status' => true,
            'order_total' => calculate_orderItems_total_by_orderId($order->id),
            'data' => $orderItems,
        ], 200);
    }
    
    public function complete_order(Request $request){
        $validator = Validator::make($request->all(), [
            'contact_number' => 'nullable',
            'contact_purson' => 'nullable',
            'delevery_note' => 'nullable',
            'payment_method' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order= Order::findOrFail($request->order_id);
        $order->order_number = generateOrderNumber();
        $order->price_subtotal = calculate_orderItems_total_by_orderId($order->id);
        $order->total_amount = calculate_orderItems_total_by_orderId($order->id);
        $order->payment_method = $request->payment_method ;
        $order->payment_status = $request->payment_method == 'Online' || $request->payment_method == 'UPI'|| $request->payment_method == 'Card' ? 'Payment Received':'Awaiting Payment' ;
        $order->is_darft =0;
        $order->order_status = 'Order Confirmed';
        $order->status = 1;
        $order->save();

        if ($request->has('seat_number') && is_array($request->seat_number)) {
            OrderSeat::where('order_id', $order->id)->delete();
            foreach ($request->seat_number as $seat) {
                OrderSeat::create([
                    'order_id' => $order->id,
                    'seat_number' => $seat,
                ]);
            }
        }
        return response()->json([
            'status'  => true,
            'message' => 'Order completed successfully',
            'data'    => $order->load('seats'),  
        ], 200);
      
    }
    
    
    public function increment_decrement_order_quantity(Request $request){

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:order_items,product_id',
            'order_id' => 'required|integer|exists:orders,id',
            'type' => 'required|in:increment,decrement',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order = Order::where('id',$request->order_id)
        ->where('user_id', $request->user()->id)
        ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.'
            ], 404);
        }

        if ($request->variation_id && $request->option_id ) {
           
            $orderItem = OrderItems::where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->where('option_id', $request->option_id)
            ->first();

        }else{

            $orderItem = OrderItems::where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->whereNull('variation_id')
            ->whereNull('option_id')
            ->first();
        }

        if ($orderItem) {
            if($request->type == 'increment'){
                $orderItem->quantity += $request->quantity;
            }
            if($request->type == 'decrement'){
                $orderItem->quantity -= $request->quantity;
                
                // If quantity becomes 0 or less, delete the item
                if ($orderItem->quantity <= 0) {
                    $orderItem->delete();
    
                    $remainingItems = OrderItems::where('order_id', $order->id)->count();
                    if ($remainingItems === 0) {
                        $order->delete(); // Optional: delete order if no items left
                        return response()->json([
                            'status' => true,
                            'message' => 'Item deleted and order deleted (no items left).',
                            'data' => []
                        ], 200);
                    }
    
                    return response()->json([
                        'status' => true,
                        'message' => 'Item quantity became 0 and was deleted.',
                        'data' => []
                    ], 200);
                }
                
            }
            $orderItem->save();
            $orderItem->load('product');
            $orderItem->product->image_url = getProductMainImage($orderItem->product_id);


            return response()->json([
                'status' => true,
                'message' => 'Order Item updated successfully.',
                'data' => $orderItem,
            ], 200);
        }
    }
    
    
    public function delete_order_item(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:order_items,product_id',
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        


        $order = Order::where('id',$request->order_id)
        ->where('user_id', $request->user()->id)
        ->first();
        
 

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.'
            ], 404);
        }
        
        
        if($request->item_id)
        {
            $orderItem = OrderItems::findOrFail($request->item_id); 
        }else{
              $orderItem = OrderItems::where('order_id', $order->id)
            ->where('product_id', $request->product_id)
            ->first();
        }

      

        if ($orderItem) {
            $orderItem->delete();
            $remainingItems = OrderItems::where('order_id', $order->id)->get();
            if ($remainingItems->isEmpty()) {
                $order->delete(); 
    
                return response()->json([
                    'status' => true,
                    'message' => 'Item deleted. Order deleted as no items are left.',
                    'data' => []
                ], 200);
            } else {
                $orderItems = OrderItems::with('product')->where('order_id', $order->id)->get();
                $orderItems->each(function ($orderItem) {
                    
                    $orderItem->product->image_url = getProductMainImage($orderItem->product_id);
                });
    
                return response()->json([
                    'status' => true,
                    'message' => 'Item deleted successfully.',
                    'data' => $orderItems
                ], 200);
            }
            
            
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Order Item Not Exists.'
            ], 200);
        }
    }
    
    
    public function draft_order_details(Request $request, $id = null)
    {
        $user = $request->user(); 
        
        if ($id != null) {

            $order = Order::with('items.product.media','seats')
            ->where('id', $id)
            ->where('user_id',$request->user()->id)
            ->where('is_darft', '1') 
            ->first();
          
        

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found.'
                ], 404);
            }
            // $user->load(['vendor.branch.coach']);

            return response()->json([
                'status' => true,
                'order' => $order,
                // 'user' => $user,
        
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Please provide Order ID'
            ], 400);
        }
    }
    
    
     public function orderDiscountUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
     
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        

        


        $order = Order::where('id', $request->order_id)
            ->where('user_id',$request->user()->id)
             ->first();
             
             
             
 
        $order->discount_amount =$request->discount_amount;
        $order->complimentary_amount =$request->complimentary_amount;
        
        $totalDiscount= $request->discount_amount + $request->complimentary_amount;
        $discounted_price =  $order->discounted_price - $totalDiscount;
        
        //         echo "<pre>";
        // print_r($totalDiscount);
        // die;
        
        $order->discounted_price =$discounted_price;
        
        $order->save();
        
         return response()->json([
                'status' => true,
                'message' => 'Order Discount updated successfully.',
                'data' => $order
            ], 200);

    }
    
    public function get_branch(Request $request)
    {
        $branches = Coach::with('seat_number')
         ->where('vendor_id',$request->user()->vendor_id)
         ->get();
        
        return response()->json([
            'response' => true,
            'message' => 'get all branch of vendor',
                'data' => [
                    'branch' => $branches,
                ],
        ]);
    }
    
    
    
}