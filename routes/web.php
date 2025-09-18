<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    Sliders,
    CategoryController,
    ProductController,
    Customers,
    CouponController,
    OrderController,
    PermissionsController,
    RoleController,
    UsersController,
    AttendeeController,
    VendorController,
    HsncodeController,
    BrandController,
    AdminController,
    SeatNumberController,
    UnitController,
    SellerMasterController,
    TransactionController,
};

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function(){
    Route::prefix('admin')->group( function() {

        /*
        *Permissions
        */
        // Route::resource('user/permissions', App\Http\Controllers\PermissionsController::class);
        // Route::delete('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionsController::class,'destroy'])->name('permissions.delete');

        Route::controller(PermissionsController::class)->group(function () {
            Route::prefix('permission')->group(function () {
                Route::get("/",'index')->name('permission.index');
                Route::post("/create-permission",'store')->name('permission.create');
                Route::put("{permissionId}/update-permission",'update')->name('permission.update');
                Route::delete("/{permissionId}/destroy-permission",'destroy')->name('permission.destroy');
                Route::post("/create-permission-group",'storePermissionGroup')->name('permission.group.create');
            });
        });

        Route::controller(RoleController::class)->group(function () {
            Route::prefix('role')->group(function () {
                Route::get("/",'index')->name('role.index');
                Route::post("/create-role",'store')->name('role.create');
                Route::put("{roleId}/update-role",'update')->name('role.update');
                Route::delete("/{roleId}/destroy-role",'destroy')->name('role.destroy');
                Route::get("/{roleId}/add-permission-to-role",'addPermissionToRole')->name('role.addPermissionToRole');
                Route::post("/{roleId}/give-permissions",'givePermissionToRole')->name('role.give-permissions');
            });
        });

        Route::controller(UsersController::class)->group(function () {
            Route::prefix('users')->name('user.')->group(function () {
                Route::get("/",'index')->name('index');
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("/{id}/show",'show')->name('show');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('destroy');
            });
        });
        Route::controller(AttendeeController::class)->group(function () {
            Route::prefix('attendees')->name('attendee.')->group(function () {
                Route::get("/",'index')->name('index');
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("/{id}/show",'show')->name('show');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('destroy');
            });
        });

        Route::controller(VendorController::class)->group(function () {
            Route::prefix('vendor')->name('vendor.')->group(function () {
                Route::get("/",'index')->name('index');
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("/{id}/show",'show')->name('show');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('destroy');
                Route::post("/coach-create",'coach_create')->name('coach.create');
            });
        });
        
        Route::controller(AdminController::class)->group(function () {
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get("/",'index')->name('index');
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("/{id}/show",'show')->name('show');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{id}",'destroy')->name('destroy');

            });
        });
        
        
        Route::get('seat-numbers', [SeatNumberController::class, 'index'])->name('seatnumber.index');
        Route::controller(SeatNumberController::class)->group(function () {
            Route::prefix('seat-numbers')->name('seatnumber.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{coach_id}",'edit')->name('edit');
                Route::put("update/",'update')->name('update');
                Route::delete("/delete/{coach_id}",'destroy')->name('destroy');
            });
        });

        

        /*
        *Roles
        */
        Route::resource('user/roles', App\Http\Controllers\RoleController::class);
        Route::delete('user/roles/{roleId}/delete', [App\Http\Controllers\RoleController::class,'destroy'])->name('roles.delete');
        Route::get('user/roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class,'addPermissionToRole'])->name('roles.give.permissions');;
        Route::put('user/roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class,'givePermissionToRole'])->name('roles.give.permissions');;

        /*
        *User Controller
        */
        // Route::resource('users', App\Http\Controllers\UsersController::class);
        // Route::delete('users/{roleId}/delete', [App\Http\Controllers\UsersController::class,'destroy']);
        // Route::get('user/{id}/activities', [App\Http\Controllers\UsersController::class, 'activity'])->name('user.activities');


        Route::resource('customers', Customers::class);
        Route::resource('slider', Sliders::class);
        Route::resource('category', CategoryController::class);
        Route::resource('units', UnitController::class);
        Route::resource('sellers', SellerMasterController::class);

        Route::controller(ProductController::class)->group( function () {
            Route::prefix('product')->group( function () {
                Route::get('','index')->name('product.index');
                Route::post('get-products-by-category','get_products_by_category_id')->name('products.get-products-by-category');
                Route::post('update-product-stock','update_product_stock')->name('products.update-product-stock');
                Route::get('basic-info-create','basic_info_create')->name('products.basic-info-create');
                Route::post('basic-info-process','basic_info_process')->name('products.add-basic-info');

                Route::get('basic-info-edit/{id?}','basic_info_edit')->name('products.basic-info-edit');
                Route::post('basic-info-edit-process','basic_info_edit_process')->name('products.add-basic-edit-info');

                Route::get('price-edit/{id?}','price_edit')->name('products.price-edit');
                Route::post('price-edit-process','price_edit_process')->name('products.price-edit-process');

                
                Route::get('inventory-edit/{id?}','inventory_edit')->name('products.inventory-edit');
                Route::post('inventory-edit-process','inventory_edit_process')->name('products.inventory-edit-process');
                
                Route::get('variation-edit/{id?}','variation_edit')->name('products.variation-edit');
                Route::post('variation-edit-process','variation_edit_process')->name('products.variation-edit-process');
                
                Route::get('product-images-edit/{id?}','product_images_edit')->name('products.product-images-edit');
                Route::post('product-gallery-save','productGalleryStore')->name('products.product-gallery-save');
                Route::post('get-product-temp-images','productTempImages')->name('products.get-product-temp-images');
                Route::post('delete-product-images','delete_product_media')->name('products.delete-product-images');
                Route::post('set-main-product-image','set_main_product_image')->name('products.set-main-product-image');
                Route::post('product-images-process','product_images_process')->name('products.product-images-process');


                Route::get('product-addons-edit/{id?}','product_addons_edit')->name('products.product-addons-edit');
                Route::post('product-addons-update','product_addons_update')->name('products.product-addons-update');

                Route::delete('delete/{id}','destroy')->name('products.delete');

                Route::get('variation/{id?}','product_variation')->name('products.variation');
                Route::post('add-variation','add_product_variation')->name('products.add-variation');
                Route::get('edit-variation','edit_product_variation')->name('products.edit-variation');
                Route::get('create-variation-option','add_variation_option')->name('products.add-variation-option');
                Route::post('add-variation-option','store_variation_option')->name('products.store-variation-option');
                Route::get('view-variation-option','view_variation_option')->name('products.view-variation-option');
                Route::get('edit-variation-option','edit_variation_option')->name('products.edit-variation-option');
                Route::post('update-variation-option','update_variation_option')->name('products.update-variation-option');

                Route::delete('delete-variation-option/{id}','delete_variation_option')->name('products.delete-variation-option');
                Route::delete('delete-variation/{variationId}','delete_variation_and_options')->name('products.delete-variation');


                Route::get('/filter','product_filter')->name('products.multiple.filter');


            });
        });

        Route::get('hsncodes', [HsncodeController::class, 'index'])->name('hsncode.index');
        Route::controller(HsncodeController::class)->group(function () {
            Route::prefix('hsncode')->name('hsncode.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::put("update/{id}",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('destroy');
            });
        });
        
        Route::get('brands', [BrandController::class, 'index'])->name('brand.index');
        Route::controller(BrandController::class)->group(function () {
            Route::prefix('brand')->name('brand.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::put("update/{id}",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('destroy');
            });
        });
        

        Route::resource('coupon', CouponController::class);

        Route::controller(OrderController::class)->group( function() {
            Route::prefix('orders')->group( function(){
                Route::get('','index')->name('order.index');
                Route::get('{id}/details','show')->name('order.details');
                Route::post('update-order-status','update_order_status')->name('order.update-order-status');
                Route::post('update-payment-status','update_payment_status')->name('order.update-payment-status');
                Route::delete('{id}/destroy','destroy')->name('order.destroy');
                Route::get('/filter','order_filter')->name('order.filter');
            });
        });

        Route::controller(TransactionController::class)->group( function() {
            Route::get('get-date-wise-total-payment','get_date_wise_total_payment')->name('transaction.get-date-wise-total-payment');
            Route::get('get-transaction-details','get_transaction_details')->name('transaction.get-transaction-details');
            Route::get('get-order-by-id/{id}','get_order_by_id')->name('transaction.get-order-by-id');
        });
    });
});

require __DIR__.'/auth.php';
