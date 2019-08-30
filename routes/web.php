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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/admin/category', 'CategoriesController');
Route::resource('/admin/product', 'ProductsController');

Route::get('/products', 'ProductsController@index')->name('product_index');
Route::get('/product/{id}', 'ProductsController@show')->name('product_show');

Route::get('/basket', 'CartController@show', 'cart_view');
Route::post('/basket', 'CartController@addItem', 'cart_add_item');
Route::post('/basket/remove', 'CartController@removeItem', 'cart_remove_item');
Route::post('/basket/update', 'CartController@update', 'cart_update');

Route::get('/checkout', 'CheckoutController@checkout', 'checkout');
Route::get('/checkout/confirm-order', 'CheckoutController@confirmOrder', 'checkout_confirm');
Route::post('/checkout/response', 'CheckoutController@checkoutResponse', 'checkout_response');
Route::get('/checkout/complete', 'CheckoutController@paymentSuccess', 'checkout_success');
Route::get('/checkout/failed', 'CheckoutController@paymentFailre', 'checkout_failure');
