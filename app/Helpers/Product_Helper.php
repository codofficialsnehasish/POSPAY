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

    if (!function_exists('calculate_cart_gst_by_userId')) {
        function calculate_cart_gst_by_userId(int $userId, int $vendorId)
        {
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