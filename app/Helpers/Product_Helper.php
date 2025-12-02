<?php
    use App\Models\Product;
    use App\Models\Cart;
    use App\Models\Coupon;

    use App\Models\User;
    use App\Models\Brand;
    use App\Models\Category;
    use App\Models\ProductVariationOption;
    use App\Models\Hsncode;
    
      use App\Models\OrderItems;
      
use Illuminate\Support\Facades\Log;
    
    if(!function_exists('getProductMainImage')){
        function getProductMainImage($productId){
            $product = Product::find($productId);

            if (!$product) {
                return null;
            }
            $mainImage = $product->getMedia('products-media')
                ->firstWhere('custom_properties.is_main', true);

            return $mainImage ? $mainImage->getUrl() : asset('images/default-product.png');
        }
    }

    if(!function_exists('calculate_cart_total_by_userId')){
        function calculate_cart_total_by_userId(int $userId, int $vendorId)
        {
            $total = 0;

            $cartItems = Cart::where('user_id', $userId)->where('vendor_id', $vendorId)->get();

            foreach ($cartItems as $cartItem) {
                
                if ($cartItem->option_id) {
                    $price = get_product_price($cartItem->product_id, $cartItem->option_id);
                }else{
                    $price = get_product_price($cartItem->product_id);
                }
                
                $total += $cartItem->quantity * $price;
            }

            return $total;
            // return number_format($total, 2, '.', '');
        }
    }

    if(!function_exists('calculate_cart_discount_by_userId')){
        function calculate_cart_discount_by_userId(int $userId, int $vendorId)
        {
            $total = 0;

            $cartItems = Cart::where('user_id', $userId)->where('vendor_id', $vendorId)->get();

            foreach ($cartItems as $cartItem) {
                $total += $cartItem->discount;
            }

            return $total;
            // return number_format($total, 2, '.', '');
        }
    }

    if(!function_exists('calculate_cart_sub_total_by_userId')){
        function calculate_cart_sub_total_by_userId(int $userId)
        {
            $total = 0;

            $cartItems = Cart::where('user_id', $userId)->get();

            foreach ($cartItems as $cartItem) {
                if ($cartItem->option_id) {
                    $price = get_product_price($cartItem->product_id, $cartItem->option_id);
                }else{
                    $price = get_product_price($cartItem->product_id);
                }
                $total += $cartItem->quantity * $price;
            }

            return $total;
        }
    }

    /*if (!function_exists('calculate_cart_gst_by_userId')) {
        function calculate_cart_gst_by_userId(int $userId)
        {
            $cgst = 0;
            $sgst = 0;

            $cartItems = Cart::where('user_id', $userId)->get();

            foreach ($cartItems as $cartItem) {
                // Get base price
                if ($cartItem->option_id) {
                    $price = get_product_price($cartItem->product_id, $cartItem->option_id);
                } else {
                    $price = get_product_price($cartItem->product_id);
                }

                $product = Product::find($cartItem->product_id);

                if (!$product) {
                    continue;
                }

                // ✅ Safely get gstRate
                $gstRate = 0;
                if ($product->hsncode_id) {
                    $hsncode = Hsncode::find($product->hsncode_id);
                    if ($hsncode) {
                        $gstRate = $hsncode->gst_rate;
                    }
                }

                $isGstIncluded = $product->is_gst_included; // 1 or 0
                $qty = $cartItem->quantity;

                $lineTotal = $price * $qty;

                if ($gstRate > 0) {
                    if ($isGstIncluded) {
                        // Extract GST from inclusive price
                        $taxAmount = ($lineTotal * $gstRate) / (100 + $gstRate);
                    } else {
                        // GST extra
                        $taxAmount = ($lineTotal * $gstRate) / 100;
                    }

                    // Divide equally into CGST and SGST
                    $cgst += $taxAmount / 2;
                    $sgst += $taxAmount / 2;
                }
            }

            return [
                'cgst' => round($cgst, 2),
                'sgst' => round($sgst, 2),
                'total_gst' => round($cgst + $sgst, 2),
            ];
        }
    }*/

    if (!function_exists('calculate_cart_gst_by_userId_old')) {
        function calculate_cart_gst_by_userId_old(int $userId, int $vendorId, $total_discount)
        {
            Log::info('Sub Total Value: ' . $total_discount);
            $cgst = 0;
            $sgst = 0;

            $cartItems = Cart::with(['product.hsncode'])
                            ->where('user_id', $userId)
                            ->where('vendor_id', $vendorId)
                            ->get();

            foreach ($cartItems as $cartItem) {

                $price = get_product_price(
                    $cartItem->product_id,
                    $cartItem->option_id
                );

                $product = $cartItem->product;
                if (!$product) continue;

                $gstRate = $product->hsncode->gst_rate ?? 0;
                $qty = $cartItem->quantity;
                $lineTotal = $price * $qty;

                if ($product->is_gst_included == 1) {
                    // Extract GST
                    $base = ($lineTotal * 100) / (100 + $gstRate);
                    $taxAmount = $lineTotal - $base;
                } else {
                    // Add GST
                    $taxAmount = ($lineTotal * $gstRate) / 100;
                }

                $cgst += $taxAmount / 2;
                $sgst += $taxAmount / 2;
            }

            return [
                'cgst' => round($cgst, 2),
                'sgst' => round($sgst, 2),
                'total_gst' => round($cgst + $sgst, 2),
            ];
        }
    }
    
    if (!function_exists('calculate_cart_gst_by_userId')) {
        function calculate_cart_gst_by_userId(int $userId, int $vendorId, $total_discount)
        {
            // Log::info('Sub Total Value: ' . $total_discount);
            $cartItems = Cart::with(['product'])
                    ->where('user_id', $userId)
                    ->where('vendor_id', $vendorId)
                    ->get();

            $totalItemPrice = 0;
            
            foreach ($cartItems as $cartItem) {
        
                $price = get_product_price(
                    $cartItem->product_id,
                    $cartItem->option_id
                );
        
                $qty = $cartItem->quantity;
        
                $totalItemPrice += ($price * $qty);
            }
            
            $subTotal = $totalItemPrice - $total_discount;
        
            // Log::info('SUB TOTAL (item price - discount): ' . $subTotal);
            
            $gstTotal = ($subTotal * 5) / 100;
            
            $cgst = $gstTotal / 2;
            $sgst = $gstTotal / 2;
        
            return [
                'sub_total' => round($subTotal, 2),
                'cgst'      => round($cgst, 2),
                'sgst'      => round($sgst, 2),
                'total_gst' => round($gstTotal, 2),
            ];
        }
    }

    if (!function_exists('calculate_gst_by_orderId_old')) {
        function calculate_gst_by_orderId_old(int $orderId)
        {
            $cgst = 0;
            $sgst = 0;

            $orderItems = OrderItems::with(['product.hsncode'])->where('order_id', $orderId)->get();

            foreach ($orderItems as $cartItem) {

                $price = get_product_price(
                    $cartItem->product_id,
                    $cartItem->option_id
                );

                $product = $cartItem->product;
                if (!$product) continue;

                $gstRate = $product->hsncode->gst_rate ?? 0;
                $qty = $cartItem->quantity;
                $lineTotal = $price * $qty;

                if ($product->is_gst_included == 1) {
                    // Extract GST
                    $base = ($lineTotal * 100) / (100 + $gstRate);
                    $taxAmount = $lineTotal - $base;
                } else {
                    // Add GST
                    $taxAmount = ($lineTotal * $gstRate) / 100;
                }

                $cgst += $taxAmount / 2;
                $sgst += $taxAmount / 2;
            }

            return [
                'cgst' => round($cgst, 2),
                'sgst' => round($sgst, 2),
                'total_gst' => round($cgst + $sgst, 2),
            ];
        }
    }
    
    if (!function_exists('calculate_gst_by_orderId')) {
        function calculate_gst_by_orderId(int $orderId, $total_app_discount)
        {
            $cgst = 0;
            $sgst = 0;
        
            $orderItems = OrderItems::with(['product'])
                            ->where('order_id', $orderId)
                            ->get();
        
            $totalItemPrice = 0;
            
            foreach ($orderItems as $item) {
        
                $price = get_product_price(
                    $item->product_id,
                    $item->option_id
                );
        
                $qty = $item->quantity;
        
                $totalItemPrice += ($price * $qty);
            }
            $subTotal = $totalItemPrice - $total_app_discount;
        
            // Log::info('Order SUB TOTAL (items - discount): ' . $subTotal);
            if ($subTotal < 0) {
                $subTotal = 0;
            }
            $gstTotal = ($subTotal * 5) / 100;
            $cgst = $gstTotal / 2;
            $sgst = $gstTotal / 2;
        
            return [
                'sub_total' => round($subTotal, 2),
                'cgst'      => round($cgst, 2),
                'sgst'      => round($sgst, 2),
                'total_gst' => round($gstTotal, 2),
            ];
        }
    }





    
    if(!function_exists('calculate_orderItems_total_by_orderId')){
        function calculate_orderItems_total_by_orderId(int $orderId)
        {
            $total = 0;

            $orderItems = OrderItems::where('order_id', $orderId)->get();

            foreach ($orderItems as $orderItem) {
                $total += $orderItem->quantity * $orderItem->price;
            }

            return $total;
        }
    }

    if(!function_exists('calculate_order_item_discount_by_order_id')){
        function calculate_order_item_discount_by_order_id(int $orderId)
        {
            $total = 0;

            $orderItems = OrderItems::where('order_id', $orderId)->get();

            foreach ($orderItems as $orderItem) {
                $total += $orderItem->app_discount;
            }

            return $total;
        }
    }


    if (!function_exists('total_vendors')) {
    function total_vendors()
    {
        return User::role('Vendor')->count();
    }
}

if (!function_exists('total_products')) {
    function total_products()
    {
        // return Product::count();
        return Product::where('vendor_id',auth()->user()->id)->count();
    }
}

if (!function_exists('total_brands')) {
    function total_brands()
    {
        // return Brand::count();
        return Brand::where('vendor_id',auth()->user()->id)->count();
    }
}

if (!function_exists('total_categories')) {
    function total_categories()
    {
        //return Category::count();
        return Category::where('vendor_id',auth()->user()->id)->count();
    }
}


if (!function_exists('get_product_price')) {
    function get_product_price($product_id, $variation_option_id = null)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return 0; 
        }
        if ($variation_option_id) {
            $option = ProductVariationOption::find($variation_option_id);
            if ($option && !$option->use_default_price) {
                return $option->price;
            }
        }

        // return $product->product_price;
        return number_format($product->product_price, 2, '.', '');
    }
}


 if(!function_exists('calculate_items_sub_total_by_userId')){
    function calculate_items_sub_total_by_userId(int $userId, $order_id)
    {
        $total = 0;

        $cartItems = OrderItems::where('order_id', $order_id)->get();

        foreach ($cartItems as $cartItem) {
            if ($cartItem->option_id) {
                $price = get_product_price($cartItem->product_id, $cartItem->option_id);
            }else{
                $price = get_product_price($cartItem->product_id);
            }
            $total += $cartItem->quantity * $price;
        }

        return $total;
    }
}

if(!function_exists('calculate_items_total_by_userId')){
    function calculate_items_total_by_userId(int $userId, int $vendorId, $order_id)
    {
        $total = 0;

        $cartItems = OrderItems::where('order_id', $order_id)->get();

        foreach ($cartItems as $cartItem) {
            
            if ($cartItem->option_id) {
                $price = get_product_price($cartItem->product_id, $cartItem->option_id);
            }else{
                $price = get_product_price($cartItem->product_id);
            }
            
            $total += $cartItem->quantity * $price;
        }

        return $total;
        // return number_format($total, 2, '.', '');
    }
}

if(!function_exists('calculate_items_discount_by_userId')){
    function calculate_items_discount_by_userId(int $userId, int $vendorId, $order_id)
    {
        $total = 0;

        $cartItems = OrderItems::where('order_id', $order_id)->get();

        foreach ($cartItems as $cartItem) {
            $total += $cartItem->discount_amount;
        }

        return $total;
        // return number_format($total, 2, '.', '');
    }
}


if (!function_exists('calculate_items_gst_by_userId_old')) {
        function calculate_items_gst_by_userId_old(int $userId, int $vendorId, $order_id, $total_discount)
        {
            Log::info('Sub Total Value: ' . $total_discount);
            $cgst = 0;
            $sgst = 0;

            $cartItems = OrderItems::with(['product.hsncode'])->where('order_id', $order_id)->get(); 
            
            // Log::info("COMPARE VALUES", [
            //     'cartItems' => $cartItems,
            // ]);

            foreach ($cartItems as $cartItem) {

                $price = get_product_price(
                    $cartItem->product_id,
                    $cartItem->option_id
                );

                $product = $cartItem->product;
                if (!$product) continue;

                $gstRate = $product->hsncode->gst_rate ?? 0;
                $qty = $cartItem->quantity;
                $lineTotal = $price * $qty;

                if ($product->is_gst_included == 1) {
                    // Extract GST
                    $base = ($lineTotal * 100) / (100 + $gstRate);
                    $taxAmount = $lineTotal - $base;
                } else {
                    // Add GST
                    $taxAmount = ($lineTotal * $gstRate) / 100;
                }

                $cgst += $taxAmount / 2;
                $sgst += $taxAmount / 2;
            }

            return [
                'cgst' => round($cgst, 2),
                'sgst' => round($sgst, 2),
                'total_gst' => round($cgst + $sgst, 2),
            ];
        }
    }
    
    if (!function_exists('calculate_items_gst_by_userId')) {
        function calculate_items_gst_by_userId(int $userId, int $vendorId, $order_id, $total_discount)
        {
            $cgst = 0;
            $sgst = 0;
        
            $orderItems = OrderItems::with(['product'])->where('order_id', $order_id)->get();
        
            $totalItemPrice = 0;

            foreach ($orderItems as $item) {
        
                $price = get_product_price(
                    $item->product_id,
                    $item->option_id
                );
        
                $qty = $item->quantity;
                $totalItemPrice += ($price * $qty);
            }
            $subTotal = $totalItemPrice - $total_discount;
            
            if ($subTotal < 0) {
                $subTotal = 0;
            }
        
            $gstTotal = ($subTotal * 5) / 100;
        
            $cgst = $gstTotal / 2;
            $sgst = $gstTotal / 2;
        
            return [
                'sub_total' => round($subTotal, 2),
                'cgst'      => round($cgst, 2),
                'sgst'      => round($sgst, 2),
                'total_gst' => round($gstTotal, 2),
            ];
        }
    }