<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'v1',
    'namespace' => 'API',
], function () {

    Route::post('login', 'Auth\LoginController@login');
    Route::get('email/verify/{id}', 'Auth\VerificationAPIController@verify')->name('verification.verify');
    Route::get('email/resend', 'Auth\VerificationAPIController@resend')->name('verification.resend');
    Route::post('forgot-passsword', 'Auth\ForgotPasswordAPIController@sendResetLinkEmail');

    Route::get('download-file', 'Auth\LoginController@downloadFile')->name('download.file')->middleware('signed');

    Route::group([
        'middleware' => ['auth:api'],
    ], function () {

        //Admin
        Route::post('aws-s3-signer-url', 'Admin\UsersAPIController@awsS3UrlForSigner');

        Route::post('admin-check_email', 'Admin\UsersAPIController@checkEmail');
        Route::post('change-password', 'Auth\LoginController@changePassword');
        Route::post('subcategories', 'Category\CategoriesAPIController@subcategories');
        Route::get('batch_request', 'Admin\UsersAPIController@batchRequest');

        Route::post('products-by-category', 'Category\CategoriesAPIController@productsByCategory');
        Route::post('userGroup-by-users', 'Category\CategoriesAPIController@productsByCategory');
        Route::get('parent-categories', 'Category\CategoriesAPIController@parentCategories');
        Route::post('user-check-email', 'User\UsersAPIController@checkEmail');
        Route::post('user-check-contact', 'User\UsersAPIController@checkContact');
        Route::post('upload-zip', 'Product\ProductsAPIController@uploadzipfile');
        Route::post('update-voucher-status', 'Voucher\VouchersAPIController@updatestatus');
        Route::post('send-voucher-manually', 'Voucher\VouchersAPIController@sendVoucherManually');
        Route::get('download-product-zip', 'Product\ProductsAPIController@downloadProductZip');

        /* Csv Log */
        Route::resource('import-csv-log', 'Import\ImportCsvLogsAPIController', [
            'only' => ['show', 'index']
        ]);

        /* Admin Orders */
        Route::post('orders-status/{order}', 'Order\OrdersAPIController@orderStatus');
        Route::post('delivery-partners/{order}', 'Order\OrdersAPIController@deliveryPartners');

        Route::get('logout', 'Auth\LoginController@logout');
    });

    Route::group([
        'middleware' => ['auth:api', 'check.permission'],
    ], function () {

        /* Front Users */
        Route::post('users-add', 'User\UsersAPIController@store');
        Route::get('users-index', 'User\UsersAPIController@index');
        Route::get('users-show/{user}', 'User\UsersAPIController@show');
        Route::post('users-update/{user}', 'User\UsersAPIController@update');
        Route::delete('users-delete/{user}', 'User\UsersAPIController@destroy');
        Route::post('users-delete-multiple', 'User\UsersAPIController@deleteAll');
        Route::get('users-export', 'User\UsersAPIController@export');
        Route::post('users-import-bulk', 'User\UsersAPIController@importBulk');

        /* Admin Orders */
        Route::apiResource('orders', 'Order\OrdersAPIController')->only(['index', 'show']);
        Route::post('orders-import-bulk', 'Order\OrdersAPIController@importBulk');
        Route::get('orders-export', 'Order\OrdersAPIController@export');

        /* Admin Users */
        Route::post('admin-register', 'Admin\UsersAPIController@register');
        Route::get('admin-users', 'Admin\UsersAPIController@index');
        Route::get('admin-users/{user}', 'Admin\UsersAPIController@show');
        Route::delete('admin-users/{user}', 'Admin\UsersAPIController@destroy');
        Route::post('admin-users/{user}', 'Admin\UsersAPIController@update');
        Route::get('admin-users-export', 'Admin\UsersAPIController@export');
        Route::post('admin-users-delete-multiple', 'Admin\UsersAPIController@deleteAll');
        Route::post('admin-users-import-bulk', 'Admin\UsersAPIController@importBulk');

        /* Roles */
        Route::apiResource('roles', 'Auth\RolesAPIController');
        Route::get('roles-export', 'Auth\RolesAPIController@export');
        Route::get('get_role_by_permissions/{id}', 'Auth\RolesAPIController@getPermissionsByRole');
        Route::post('roles-delete-multiple', 'Auth\RolesAPIController@deleteAll');

        /* Permissions */
        Route::apiResource('permissions', 'Auth\PermissionsAPIController');
        Route::post('permissions-delete-multiple', 'Auth\PermissionsAPIController@deleteAll');
        Route::get('permissions-export', 'Auth\PermissionsAPIController@export');

        /* Brands */
        Route::apiResource('brands', 'Brand\BrandsAPIController');
        Route::get('brands-export', 'Brand\BrandsAPIController@export');
        Route::post('brands-delete-multiple', 'Brand\BrandsAPIController@deleteAll');

        /*Manage User Groups*/
        Route::apiResource('user-groups', 'UserGroup\UserGroupsAPIController');
        Route::post('user-group-delete-multiple', 'UserGroup\UserGroupsAPIController@deleteAll');
        Route::get('user-groups-export', 'UserGroup\UserGroupsAPIController@export');

        /*Manage Catalogues*/
        Route::apiResource('catalogues', 'Catalogue\CataloguesAPIController');
        Route::post('catalogues-delete-multiple', 'Catalogue\CataloguesAPIController@deleteAll');
        Route::get('catalogues-export', 'Catalogue\CataloguesAPIController@export');

        /* Categories */
        Route::apiResource('categories', 'Category\CategoriesAPIController');
        Route::get('categories-export', 'Category\CategoriesAPIController@export');
        Route::post('categories-delete-multiple', 'Category\CategoriesAPIController@deleteAll');
        Route::post('set_unset_permission_to_role', 'Auth\PermissionsAPIController@setUnsetPermissionToRole');

        /* Product */
        Route::post('products/{product}', 'Product\ProductsAPIController@update');
        Route::apiResource('products', 'Product\ProductsAPIController');
        Route::post('product-delete-multiple', 'Product\ProductsAPIController@deleteAll');
        Route::get('products-export', 'Product\ProductsAPIController@export');
        Route::post('products-bulk', 'Product\ProductsAPIController@importBulk');
        Route::post('delete-images', 'Product\ProductsAPIController@deleteImage');
        Route::post('upload-images', 'Product\ProductsAPIController@uploadImage');

        /* Voucher */
        Route::apiResource('vouchers', 'Voucher\VouchersAPIController');
        Route::post('vouchers/{voucher}', 'Voucher\VouchersAPIController@update');
        Route::get('vouchers-export', 'Voucher\VouchersAPIController@export');
        Route::post('vouchers-bulk', 'Voucher\VouchersAPIController@importBulk');

        /* Contact */
        Route::apiResource('contacts', 'Contact\ContactsAPIController')->only(['index', 'show']);
        Route::get('contacts-export', 'Contact\ContactsAPIController@export');

        /* Admin Dashboard */
        Route::get('dashboard', 'Dashboard\DashboardsAPIController@index');
    });
});
