<?php

use Illuminate\Support\Facades\Route;

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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function () {
	Route::get('/', 'HomeController@index')->name('home');
	Route::get('/home/show', 'HomeController@show')->name('home/show');

	//Transaction
	Route::get('/transaction', 'TransactionController@index')->name('transaction');
	Route::post('/transaction/getData', 'TransactionController@getData')->name('transaction/getData');
	Route::get('/transaction/create', 'TransactionController@create')->name('transaction/create');
	Route::post('/transaction/create', 'TransactionController@store')->name('transaction/create');
	Route::get('/transaction/{id}', 'TransactionController@show')->name('transaction/show');
	Route::post('/transaction/updateStatus', 'TransactionController@updateStatus')->name('transaction/updateStatus');
	Route::get('/transaction/nota/{id}', 'TransactionController@downloadNota')->name('transaction/nota');
	
	//Report
	Route::get('/report', 'ReportController@index')->name('report');
	Route::post('/report/chart', 'ReportController@getMonthlyTransactionData')->name('report/chart');

	//User
	Route::get('/user', 'UserController@index')->name('user');
	Route::post('/user/getData', 'UserController@getData')->name('user/getData');
	Route::post('/user/store', 'UserController@store')->name('user/store');
	Route::get('/user/{id}', 'UserController@show')->name('user');
	Route::post('/user/update', 'UserController@update')->name('user/update');
	Route::post('/user/password', 'UserController@changePassword')->name('user/password');
	Route::post('/user/profile', 'UserController@changeProfile')->name('user/profile');
    Route::post('/user/delete/{id}','UserController@destroy')->name('user/delete');

	//Role
	Route::get('/role', 'RoleController@index')->name('role');
	Route::post('/role/getData', 'RoleController@getData')->name('role/getData');
	Route::get('/role/create', 'RoleController@create')->name('role/create');
	Route::post('/role/create', 'RoleController@store')->name('role/create');
	Route::get('/role/edit/{id}', 'RoleController@edit')->name('role/edit');
	Route::post('/role/edit/{id}', 'RoleController@update')->name('role/edit');
    Route::post('/role/delete/{id}','RoleController@destroy')->name('role/delete');

	//Customer
	Route::get('/customer', 'CustomerController@index')->name('customer');
	Route::post('/customer/getData', 'CustomerController@getData')->name('customer/getData');
	Route::post('/customer/store', 'CustomerController@store')->name('customer/store');
	Route::post('/customer/update', 'CustomerController@update')->name('customer/update');
	Route::get('/customer/{id}', 'CustomerController@show')->name('customer');
    Route::post('/customer/delete/{id}','CustomerController@destroy')->name('customer/delete');

	//Product
	Route::get('/product', 'ProductController@index')->name('product');
	Route::post('/product/getData', 'ProductController@getData')->name('product/getData');
	Route::post('/product/store', 'ProductController@store')->name('product/store');
	Route::get('/product/{id}', 'ProductController@show')->name('product');
	Route::post('/product/update', 'ProductController@update')->name('product/update');
    Route::post('/product/delete/{id}','ProductController@destroy')->name('product/delete');

	//Product Type
	Route::get('/product_type', 'ProductTypeController@index')->name('product_type');
	Route::post('/product_type/getData', 'ProductTypeController@getData')->name('product_type/getData');
	Route::post('/product_type/store', 'ProductTypeController@store')->name('product_type/store');
	Route::post('/product_type/update', 'ProductTypeController@update')->name('product_type/update');
	Route::get('/product_type/{id}', 'ProductTypeController@show')->name('product_type');
    Route::post('/product_type/delete/{id}','ProductTypeController@destroy')->name('product_type/delete');
});