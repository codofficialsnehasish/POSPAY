<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Cart;

use App\Models\Product;
use App\Models\Category;

use App\Models\ProductVariation;
use App\Models\ProductVariationOption;
use Illuminate\Support\Facades\Log;

class CartAPI extends Controller
{
    // public function add_to_cart(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'product_id' => 'required|integer|exists:products,id',
    //         'quantity' => 'required|integer|min:1',
    //         'user_id' => 'required|integer|exists:users,id',
  

    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $existingCartItem = Cart::where('user_id', $request->user_id)
    //     ->where('product_id', $request->product_id)
    //     ->where('variation_id', $request->variation_id)
    //     ->where('option_id', $request->option_id)
    //     ->first();

    //     if ($existingCartItem) {
 
    //         $existingCartItem->quantity += $request->quantity;
    //         $existingCartItem->save();

    //         $existingCartItem->load('product');
    //         $existingCartItem->product->image_url = getProductMainImage($existingCartItem->product_id);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Cart updated successfully.',
    //             'data' => $existingCartItem,
    //         ], 200);
    //     }

    //     $product = Product::findOrFail($request->product_id);
    //     $option= ProductVariationOption::findOrFail($request->option_id);
    //     $product_title = $product->name . "-" . $option->name; 



    //     $cartItem = Cart::create([
    //         'user_id' => $request->user_id,
    //         'product_id' => $request->product_id,
    //         'variation_id' => $request->variation_id,
    //         'option_id' => $request->option_id,
    //         'quantity' => $request->quantity,
    //         'product_title' => $product_title ?? '', 
    //     ]);

    //     $cartItem->load('product');

    //     $cartItem->product->image_url = getProductMainImage($cartItem->product_id);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product added to cart successfully.',
    //         'data' => $cartItem,
    //     ], 201);
    // }



    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'items' => 'nullable|array|min:1',
            'items.*.variation_id' => 'nullable|integer|exists:product_variations,id',
            'items.*.option_id' => 'nullable|integer|exists:product_variation_options,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $vendorId = $request->vendorId;

        $product = Product::findOrFail($request->product_id);
        $cartItems = [];
        
        if (!empty($request->items)) {

            foreach ($request->items as $item) {
                $existingCartItem = Cart::where('user_id', $request->user()->id)
                    ->where('vendor_id',$vendorId)
                    ->where('product_id', $request->product_id)
                    ->where('variation_id', $item['variation_id'])
                    ->where('option_id', $item['option_id'])
                    ->first();
    
                if ($existingCartItem) {
                    $existingCartItem->quantity += $item['quantity'];
                    $existingCartItem->save();
                } else {
                    $option = ProductVariationOption::findOrFail($item['option_id']);
                    $product_title = $product->name . '-' . $option->name;
    
                    $existingCartItem = Cart::create([
                        'user_id' => $request->user()->id,
                        'vendor_id' => $vendorId,
                        'product_id' => $request->product_id,
                        'variation_id' => $item['variation_id'],
                        'option_id' => $item['option_id'],
                        'quantity' => $item['quantity'],
                        'product_title' => $product_title,
                    ]);
                }
    
                $existingCartItem->load('product');
                $existingCartItem->product->image_url = getProductMainImage($existingCartItem->product_id);
                $cartItems[] = $existingCartItem;
            }
        }else{
            
             // Non-variant product
            $existingCartItem = Cart::where('user_id', $request->user()->id)
            ->where('vendor_id',$vendorId)
            ->where('product_id', $request->product_id)
            ->whereNull('variation_id')
            ->whereNull('option_id')
            ->first();

            $qty = $request->quantity ?? 1;
            $price = get_product_price($request->product_id);
            $product_title = $product->name;

            if ($existingCartItem) {
                $existingCartItem->quantity += $qty;
                $existingCartItem->save();
            } else {
                $existingCartItem = Cart::create([
                    'user_id' => $request->user()->id,
                    'vendor_id' => $vendorId,
                    'product_id' => $request->product_id,
                    'quantity' =>  $request->quantity,
                    'product_title' => $product_title,
                ]);
            }

            $existingCartItem->load('product');
            $existingCartItem->product->image_url = getProductMainImage($existingCartItem->product_id);
            $cartItems[] = $existingCartItem;
            
            
        }

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully.',
            'data' => $cartItems,
        ]);
    }


    // public function cart_items(Request $request){
    //     $cart_items = Cart::with('product')->where('user_id', $request->user()->id)->get();

    //     $cart_items->each(function ($cartItem) {
    //         // Load the media collection for each product
    //         $cartItem->product->image_url = getProductMainImage($cartItem->product_id);
    //     });

    //     return response()->json([
    //         'status' => true,
    //         'cart_total' => calculate_cart_total_by_userId($request->user()->id),
    //         'data' => $cart_items,
    //     ], 200);
    // }
    
    
    /*public function cart_items(Request $request)
    {
        $vendorId = $request->vendorId;
        $gstRates = [];

        $cart_items = Cart::with(['product', 'product.hsncode', 'variationOption'])->where('user_id', $request->user()->id)->where('vendor_id',$vendorId)->get();
        
        $cart_items->each(function ($cartItem) use ($request, &$gstRates) {
            // Load product image
            $cartItem->product->image_url = getProductMainImage($cartItem->product_id);
            
            $cartItem->variationOption->quantity = $cartItem->product->vendorStock($request->vendorId, $cartItem->variationOption->id);

            // Calculate correct price based on option_id
            if ($cartItem->option_id) {
                $cartItem->price = get_product_price($cartItem->product_id, $cartItem->option_id);
            } else {
                $cartItem->price = get_product_price($cartItem->product_id);
            }

            $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
            // $cartItem->subtotal = number_format($cartItem->price * $cartItem->quantity, 2, '.', '');

            // Collect GST rate for checking
            $gstRates[] = $cartItem->product->hsncode->gst_rate ?? 0;
        });

        // Check if all GST rates are same
        $allSameGst = count(array_unique($gstRates)) === 1;

        $gst = calculate_cart_gst_by_userId($request->user()->id);
        $total_discount = 0;
        $item_total = calculate_cart_total_by_userId($request->user()->id, $vendorId);
        if(!$allSameGst){
            $cart_items->each(function ($cartItem) {
                $total_discount = $cartItem->discount;
            });
            $item_total -= $total_discount;
        }



        return response()->json([
            'status' => true,
            'item_total' => $item_total,
            'discount' => $total_discount,
            'compelementary' => 0.00,
            'sgst' => $gst['sgst'] ?? 0.00,
            'cgst' => $gst['cgst'] ?? 0.00,
            'total_gst' => $gst['total_gst'] ?? 0.00,
            'grand_total' => $item_total + ($gst['total_gst'] ?? 0.00),
            'is_gst_same' => $allSameGst ? 1 : 0,
            'data' => $cart_items,
        ], 200);
    }*/

    public function cart_items(Request $request)
    {
        
        $userId   = $request->user()->id;
        $vendorId = $request->vendorId;

        $gstRates = [];
        $total_discount = 0;

        $cart_items = Cart::with(['product', 'product.hsncode', 'variationOption'])
            ->where('user_id', $userId)
            ->where('vendor_id', $vendorId)
            ->get();

        $cart_items->each(function ($cartItem) use ($request, &$gstRates, &$total_discount) {

            // Product Image
            $cartItem->product->image_url = getProductMainImage($cartItem->product_id);

            // Stock
            $cartItem->variationOption->quantity =
                $cartItem->product->vendorStock(
                    $request->vendorId,
                    $cartItem->variationOption->id
                );

            // Price
            if ($cartItem->option_id) {
                $cartItem->price = get_product_price($cartItem->product_id, $cartItem->option_id);
            } else {
                $cartItem->price = get_product_price($cartItem->product_id);
            }

            // Subtotal
            // $cartItem->subtotal = round($cartItem->price * $cartItem->quantity,2);
            $cartItem->subtotal = (float) number_format(
                    $cartItem->price * $cartItem->quantity,
                    2,
                    '.',
                    ''
                );


            // Discount
            $total_discount += floatval($cartItem->discount ?? 0);

            // Collect GST rate
            $gstRates[] = floatval($cartItem->product->hsncode->gst_rate ?? 0);
        });

        // GST same or not
        $allSameGst = count(array_unique($gstRates)) === 1;
        $req_discount = $request->discount ?? 0.00;
        $req_complementary = $request->complementary ?? 0.00;
        if($allSameGst){
            $total_discount = ($req_discount + $req_complementary) ?? 0.00;
            $discount = $req_discount;
        }else{
            $discount = $total_discount;
        }
        
        // Get GST
        
        
        $gst = calculate_cart_gst_by_userId($userId, $vendorId, $total_discount);

        // Item total BEFORE GST
        $item_total = calculate_cart_total_by_userId($userId, $vendorId);

        // Apply discount (always)
        $sub_total = $item_total - $total_discount;
        
        return response()->json([
            'status'        => true,
            'item_total'    => round($item_total, 2),
            'discount'      => round($discount, 2) ?? 0.00,
            'compelementary'=> isset($req_complementary) ? (int)$req_complementary : '0.00',
            // 'discount_subtotal' => round($item_total - $total_discount, 2),
            'discount_subtotal' => (float) number_format(
                    $item_total - $total_discount,
                    2,
                    '.',
                    ''
                ),
            'sgst'          => $gst['sgst'],
            'cgst'          => $gst['cgst'],
            'total_gst'     => $gst['total_gst'],
            'grand_total' => round($sub_total + $gst['total_gst'], 2),
            'rounded_grand_total' => round($sub_total + $gst['total_gst']).'.00',
            'is_gst_same'   => $allSameGst ? 1 : 0,
            'data'          => $cart_items,
        ], 200);

    }



    public function increment_decrement_cart_quantity(Request $request){

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:carts,product_id',
            'type' => 'required|in:increment,decrement',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // $existingCartItem = Cart::where('user_id', $request->user()->id)
        //             ->where('product_id', $request->product_id)
        //             ->first();

        $vendorId = $request->vendorId;

        $existingCartItem = Cart::where('user_id', $request->user()->id)
            ->where('vendor_id',$vendorId)
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->where('option_id', $request->option_id)
            ->first();


        


        if ($existingCartItem) {
            if($request->type == 'increment'){
                $existingCartItem->quantity += $request->quantity;
            }
            if($request->type == 'decrement'){
                $existingCartItem->quantity -= $request->quantity;
            }
            $existingCartItem->save();

            $existingCartItem->load('product');
            $existingCartItem->product->image_url = getProductMainImage($existingCartItem->product_id);

            return response()->json([
                'status' => true,
                'message' => 'Cart Item updated successfully.',
                'data' => $existingCartItem,
            ], 200);
        }
    }

    public function add_cart_item_discount(Request $request){

        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|integer|exists:carts,id',
            'discount_amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // $vendorId = $request->vendorId;

        $existingCartItem = Cart::find($request->cart_id);


        if ($existingCartItem) {
            $existingCartItem->discount = $request->discount_amount;
            $existingCartItem->save();

            return response()->json([
                'status' => true,
                'message' => 'Cart Item updated successfully.',
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Cart Item not found.',
            ], 200);   
        }
    }

    public function delete_cart_item(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:carts,product_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $vendorId = $request->vendorId;

        $existingCartItem = Cart::where('user_id', $request->user()->id)
            ->where('vendor_id',$vendorId)
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->where('option_id', $request->option_id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->delete();
            
            $CartItems = Cart::with('product')->where('user_id', $request->user()->id)->where('vendor_id',$vendorId)->get();

            $CartItems->each(function ($cartItem) {
                // Load the media collection for each product
                $cartItem->product->image_url = getProductMainImage($cartItem->product_id);
            });

            return response()->json([
                'status' => true,
                'message' => 'Item deleted successfully.',
                'data' => $CartItems
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Cart Item Not Exists.'
            ], 200);
        }
    }

    public function clear_cart(Request $request){
        $vendorId = $request->vendorId;
        Cart::where('user_id', $request->user()->id)->where('vendor_id',$vendorId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cart Cleared successfully.',
        ], 200);
    }
}