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

        $product = Product::findOrFail($request->product_id);
        $cartItems = [];
        
        if (!empty($request->items)) {

            foreach ($request->items as $item) {
                $existingCartItem = Cart::where('user_id', $request->user()->id)
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
    
    
    public function cart_items(Request $request)
{
    $cart_items = Cart::with(['product', 'variationOption'])->where('user_id', $request->user()->id)->get();

    $cart_items->each(function ($cartItem) {
        // Load product image
        $cartItem->product->image_url = getProductMainImage($cartItem->product_id);

        // Calculate correct price based on option_id
        if ($cartItem->option_id) {
            $cartItem->price = get_product_price($cartItem->product_id, $cartItem->option_id);
        } else {
            $cartItem->price = get_product_price($cartItem->product_id);
        }

        $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
    });

    return response()->json([
        'status' => true,
        'cart_total' => calculate_cart_total_by_userId($request->user()->id),
        'data' => $cart_items,
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


        $existingCartItem = Cart::where('user_id', $request->user()->id)
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

    public function delete_cart_item(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:carts,product_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $existingCartItem = Cart::where('user_id', $request->user()->id)
        ->where('product_id', $request->product_id)
        ->first();

        if ($existingCartItem) {
            $existingCartItem->delete();
            
            $CartItems = Cart::with('product')->where('user_id', $request->user()->id)->get();

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
        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cart Cleared successfully.',
        ], 200);
    }
}