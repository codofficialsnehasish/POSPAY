<?php
    use App\Models\Product;
    use App\Models\Cart;
    use App\Models\Coupon;

    use App\Models\User;
    use App\Models\Brand;
    use App\Models\Category;
    use App\Models\ProductVariationOption;
    
      use App\Models\OrderItems;
    
    if(!function_exists('getProductMainImage')){
        function getProductMainImage($productId){
            $product = Product::find($productId);

            if (!$product) {
                return null;
            }
            $mainImage = $product->getMedia('products-media')
                ->firstWhere('custom_properties.is_main', true);

            return $mainImage ? $mainImage->getUrl() : null;
        }
    }

    if(!function_exists('calculate_cart_total_by_userId')){
        function calculate_cart_total_by_userId(int $userId)
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
        return Product::count();
    }
}

if (!function_exists('total_brands')) {
    function total_brands()
    {
        return Brand::count();
    }
}

if (!function_exists('total_categories')) {
    function total_categories()
    {
        return Category::count();
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

        return $product->product_price;
    }
}