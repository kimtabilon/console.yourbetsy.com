<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();

// SIGNUP RESELLER
Route::group(['prefix' => 'signup', 'middleware' => ['guest']], function() {
    Route::get('/','SignUpController@create');
    Route::post('/','SignUpController@store');
    Route::get('/login','SignUpController@SignUpLoginForm');
    Route::get('/success', 'SignUpController@signupsuccess');
});

// FORGOT PASSWORD RESELLER
Route::group(['prefix' => 'forgotpassword', 'middleware' => ['guest']], function() {
    Route::get('/','ForgotPasswordController@create');
    Route::post('/','ForgotPasswordController@verifyinfo');

});

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/users/logout', 'Auth\LoginController@AdminLogout')->name('admin.logout');

// RESELLER
Route::get('/', 'Auth\ResellerController@showLoginForm')->name('vendor.login');
Route::prefix('vendor')->group(function() {
    // Route::get('/login', 'Auth\ResellerController@showLoginForm')->name('vendor.login');
    Route::post('/login', 'Auth\ResellerController@login')->name('vendor.submit');
    // Route::get('/', 'ResellerController@index')->name('reseller.dashboard');
    // Route::get('/logout', 'Auth\ResellerController@logout')->name('vendor.logout');
    // Route::get('/dashboard', 'Reseller\ResellerController@index')->name('reseller.dashboard');
});
Route::group(['prefix' => 'vendor', 'middleware' => ['child_vendor','auth:reseller']], function() {
    Route::get('/secondary-vendor-management/secondary-list', 'Reseller\ResellerChildManagementController@child_list')->name('reseller.child.list');
    Route::match(['put', 'patch'],'/child-management/child-list/add-child', 'Reseller\ResellerChildManagementController@add_child');
});


Route::group(['prefix' => 'vendor', 'middleware' => ['auth:reseller']], function() {
    

    Route::get('/dashboard', 'Reseller\ResellerController@index')->name('vendor.dashboard');
    Route::get('/logout', 'Auth\ResellerController@logout')->name('reseller.logout');
    Route::get('/update/primary-information', 'Reseller\ProfileController@index')->name('reseller.updateprofile');
    Route::match(['put', 'patch'],'/profile/update_profile', 'Reseller\ProfileController@updateprofile');
    Route::post('/profile/update_profile', 'Reseller\ProfileController@updateprofile');

    /* Route::get('/secondary-vendor-management/secondary-list', 'Reseller\ResellerChildManagementController@child_list')->name('reseller.child.list');
    Route::match(['put', 'patch'],'/child-management/child-list/add-child', 'Reseller\ResellerChildManagementController@add_child') ; */
    Route::post('/secondary-vendor-management/secondary-vendor-list/view', 'Reseller\ResellerChildManagementController@view_child_details');
    Route::match(['put', 'patch'],'/child-management/change_status','Reseller\ResellerChildManagementController@child_change_status');
    Route::get('/about-us', 'Reseller\ProfileController@about_us')->name('reseller.about_us');
    Route::match(['put', 'patch'],'/about-us/update','Reseller\ProfileController@update_aboutus');
    Route::get('/shipping-policy', 'Reseller\ProfileController@shipping_policy')->name('reseller.shipping_policy');
    Route::match(['put', 'patch'],'/shipping-policy/update','Reseller\ProfileController@update_shipping_policy');
    Route::get('/return-policy', 'Reseller\ProfileController@return_policy')->name('reseller.return_policy');
    Route::match(['put', 'patch'],'/return-policy/update','Reseller\ProfileController@update_return_policy');

    Route::get('/payment-information', 'Reseller\ProfileController@payment_information')->name('reseller.payment_information');
    Route::match(['put', 'patch'],'/payment-information/update','Reseller\ProfileController@update_payment_information');

    Route::get('/items', 'Reseller\ResellerItemController@index')->name('reseller.items');
    Route::post('/items/add','Reseller\ResellerItemController@add_item');
    Route::post('/items/update','Reseller\ResellerItemController@update_item');
    Route::post('/items/view', 'Reseller\ResellerItemController@item_details');
    Route::post('/items/sub-category', 'Reseller\ResellerItemController@sub_category');
    Route::post('/items/update_pq','Reseller\ResellerItemController@update_pq_item');
    Route::post('/items/categories','Reseller\ResellerItemController@getCategoriesPerLevel');
    Route::post('/items/catfirstlevel','Reseller\ResellerItemController@categoryList');

    Route::get('/order','Reseller\ResellerOrderController@index')->name('reseller.order');
    Route::post('/order-ship','Reseller\ResellerOrderController@ship_order')->name('reseller.order-ship');
    Route::post('/order-items','Reseller\ResellerOrderController@order_items')->name('reseller.order-items');
    Route::post('/order-refund','Reseller\ResellerOrderController@refund_order')->name('reseller.order-refund');
    Route::post('/order-details','Reseller\ResellerOrderController@view_order_details')->name('reseller.order-items');

    Route::get('/shipment-rate','Reseller\ResellerShipmentRateController@index')->name('reseller.shipment-rate');
    Route::get('/shipment-rate/export','Reseller\ResellerShipmentRateController@export')->name('reseller.shipment-rate_export');
    Route::post('/shipment-rate/import','Reseller\ResellerShipmentRateController@import');
});

// ADMIN
Route::prefix('admin')->group(function() {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    // Route::get('/', 'LoginController@index')->name('admin.dashboard');  
    Route::get('/logout', 'Auth\LoginController@AdminLogout')->name('logout');
});

Route::group(['middleware' => ['auth:web']], function () {
    Route::prefix('admin')->group(function() {
        Route::get('/dashboard', 'Admin\AdminDashboardController@index')->name('admin.dashboard');
        Route::get('/vendor/verify', 'Admin\ResellerManagementController@pending_resellers')->name('admin.reseller-verify');
        Route::match(['put', 'patch'],'/vendor/change_status','Admin\ResellerManagementController@reseller_change_status');
        Route::match(['put', 'patch'],'/vendor/change_status_sd','Admin\ResellerManagementController@reseller_change_status_SD');
        Route::post('/vendor/verify/view', 'Admin\ResellerManagementController@view_reseller_detalis');
        Route::post('/vendor/verify/view-status-id', 'Admin\ResellerManagementController@view_reseller_detalis_bystatusID');
        Route::get('/vendor/suspend-disable', 'Admin\ResellerManagementController@active_resellers')->name('admin.reseller-suspend_disable');
        Route::get('/vendor/profile-update-request', 'Admin\ResellerManagementController@reseller_profile_update_request')->name('admin.reseller-profile-update-request');
        Route::match(['put', 'patch'],'/vendor/profile-update-request','Admin\ResellerManagementController@reseller_change_profilerequest_status');
        Route::post('/vendor/profile-update-request/view-requested', 'Admin\ResellerManagementController@requested_details');
        Route::get('/product-management/verify', 'Admin\ProductManangementController@pending_products')->name('admin.product-management_verify');
        Route::post('/product-management/verify/view', 'Admin\ProductManangementController@item_details')->name('admin.product-management_verify_view');
        Route::match(['put', 'patch'],'/product-management/verify/change_status','Admin\ProductManangementController@product_change_status');
        Route::match(['put', 'patch'],'/product-management/verify/change_status_sd','Admin\ProductManangementController@product_change_status_sd');
        Route::match(['put', 'patch'],'/product-management/verify/decline','Admin\ProductManangementController@product_decline');
        Route::post('/product-management/verify/view_decline', 'Admin\ProductManangementController@view_decline')->name('admin.product-management_verify_view_decline');
        Route::get('/product-management/suspend-disable', 'Admin\ProductManangementController@active_products')->name('admin.product-management_suspend_disable');
        Route::get('/category', 'Admin\CategoryManagementController@index')->name('admin.category');
        Route::post('/category/add', 'Admin\CategoryManagementController@add')->name('admin.category-add');
        Route::post('/category/update', 'Admin\CategoryManagementController@update')->name('admin.category-update');
        Route::post('/category/change_status', 'Admin\CategoryManagementController@change_status')->name('admin.category-change_status');
        Route::post('/category/sub-change_status', 'Admin\CategoryManagementController@change_status')->name('admin.category-change_status');
        Route::post('/category/add-sub', 'Admin\CategoryManagementController@add_sub')->name('admin.category-add_sub');
        Route::post('/category/update-sub', 'Admin\CategoryManagementController@update_sub')->name('admin.category-update_sub');
        Route::post('/category/sub-category-list', 'Admin\CategoryManagementController@subcategory_list')->name('admin.category-subcategory_list');
        Route::post('/category/sub-change_status', 'Admin\CategoryManagementController@change_status_sub')->name('admin.category-change_status_sub');
        Route::post('/category/category-delete', 'Admin\CategoryManagementController@delete')->name('admin.category-delete');
        Route::post('/category/sub-category-delete', 'Admin\CategoryManagementController@delete_sub')->name('admin.sub-category-delete');
    });
});

Route::get('mailable', function () {
    $resellersprofiles = App\ResellersProfiles::find(4);
    $resellersprofiles->action_type = "0";
    return new App\Mail\VerifyResellers($resellersprofiles);
});

Route::prefix('content')->group(function() {
    Route::get('/details/{sku?}', 'ContentController@details')->name('content.details');
    Route::get('/description/{sku?}', 'ContentController@description')->name('content.description');
    Route::get('/manufacturer-profile/{name?}', 'ContentController@manufacturer_profile')->name('content.description');
    Route::get('/gallery/{sku?}', 'ContentController@gallery')->name('content.gallery');
    Route::get('/policies/{sku?}', 'ContentController@manufacturer_policies')->name('content.gallery');
});

