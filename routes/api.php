<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\{
    AuthenticationController,
    SliderAPI,
    CategoeyAPI,
    ProductAPI,
    CartAPI,
    CouponsAPI,
    OrderAPI,
    AddressAPI,
    DashboardController,
    TransactionApi,
    PurchaseController,
};


Route::post('/login', [AuthenticationController::class, 'login']);
// Route::get('get-products-by-category/{id}', [ProductAPI::class, 'get_products_by_category']);
// Route::get('get-category-wise-all-product', [ProductAPI::class, 'get_category_wise_product']);
// Route::post('get-products-by-barcode', [ProductAPI::class, 'get_product_by_barcode']);
Route::post('add-to-cart', [CartAPI::class, 'add_to_cart']);
// Route::get('cart-items', [CartAPI::class, 'cart_items']);
// Route::post('update-cart-quantity', [CartAPI::class, 'increment_decrement_cart_quantity']);



Route::middleware('auth:sanctum')->group( function () {
    Route::post('/update-profile', [AuthenticationController::class, 'update_profile']);
    Route::get('/get-user-data', [AuthenticationController::class, 'get_user_data']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
   
    Route::get('sliders', [SliderAPI::class, 'index']);
    
    Route::get('/get-vendors', [AuthenticationController::class, 'get_vendors']);
    Route::post('/add-vendor', [AuthenticationController::class, 'update_vendor']);
    Route::get('get-categories/{id?}', [CategoeyAPI::class, 'index']);
    
    Route::post('/add-pos-details', [AuthenticationController::class, 'add_pos_details']);
    
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard-products', [DashboardController::class, 'products']);
    
    Route::get('get-products-by-category/{id}', [ProductAPI::class, 'get_products_by_category']);
    Route::get('get-category-wise-all-product', [ProductAPI::class, 'get_category_wise_product']);
    Route::post('get-products-by-barcode', [ProductAPI::class, 'get_product_by_barcode']);

    Route::controller(ProductAPI::class)->group( function () {
        Route::get('get-products/{id?}','index');
        Route::get('search-product','search_product');
        
    });
    Route::post('add-to-cart', [CartAPI::class, 'add_to_cart']);
    Route::post('update-cart-quantity', [CartAPI::class, 'increment_decrement_cart_quantity']);
    // Route::post('add-to-cart', [CartAPI::class, 'add_to_cart']);

    Route::get('cart-items', [CartAPI::class, 'cart_items']);
    Route::controller(CartAPI::class)->group( function() {


        // Route::post('update-cart-quantity','increment_decrement_cart_quantity');
        Route::post('delete-cart-item','delete_cart_item');
        Route::get('clear-cart','clear_cart');
    });

    Route::get('get-coupons', [CouponsAPI::class, 'index']);

    Route::controller(OrderAPI::class)->group( function() {
        
        Route::post('create-razorpay-order','createRazorpayOrder');
        Route::post('place-order','place_order');

        Route::get('order-history','order_history');
        Route::get('order-details/{id?}','order_details');
        Route::post('cancel-order','cancel_order');
        Route::get('search-order','searchOrder');
        Route::post('place-draft-order','place_draft_order');
        Route::get('draft-order-history','draft_order_history');
        Route::post('update-order','orderUpdate');
        Route::get('order-items','order_items');
        Route::post('complete-order','complete_order');
        Route::post('order-quantity-update','increment_decrement_order_quantity');
        Route::post('order-item-delete','delete_order_item');
        Route::get('draft-order-details/{id?}','draft_order_details');
        Route::post('order-discount-update','orderDiscountUpdate');
        Route::get('get-branch','get_branch');
        
    });

    Route::controller(AddressAPI::class)->group( function() {
        Route::post('save-address','save_address');
        Route::post('save-as-default-address','save_as_default_address');
        Route::get('get-saved-address','get_saved_address');
    });

    Route::controller(TransactionApi::class)->group( function() {
        Route::get('get-date-wise-total-payment','get_date_wise_total_payment');
        Route::get('get-transaction-details','get_transaction_details');
        Route::get('get-order-by-order-id/{id?}','get_order_by_id');
    });

    Route::controller(PurchaseController::class)->group( function() {
        Route::get('get-all-purchase','index');
        Route::get('search-products','search_products');
        Route::get('get-purchasing-products','get_products');
        Route::get('get-sellers','get_seller');
        Route::post('purchase-store','storePurchase');
    });
});