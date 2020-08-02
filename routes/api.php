<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function (){
	
	//public
	Route::post('login', 'AuthController@login');
	Route::post('register', 'AuthController@register');

	Route::get('categories/random/{count}', 'CategoryController@random');
	Route::get('categories','CategoryController@index');
	Route::get('categories/slug/{slug}', 'CategoryController@slug');


	Route::get('books/top/{count}','BookController@top');
	Route::get('books','BookController@index');
	Route::get('books/slug/{slug}', 'BookController@slug');
	Route::get('books/search/{keyword}','BookController@search');
	Route::get('books/{id}', 'BookController@findBook');

	Route::get('provinces','ShopController@provinces');
	Route::get('cities','ShopController@cities');
	Route::get('couriers','ShopController@couriers');

	Route::get('contact','SettingContactController@index');
	Route::get('about', 'AboutController@index');
	Route::get('spanduks', 'SpandukController@index');

	//private
	Route::middleware(['auth:api'])->group(function () {
		Route::post('logout', 'AuthController@logout');
		Route::post('shipping', 'ShopController@shipping');
        Route::post('services', 'ShopController@services');
        Route::post('payment', 'ShopController@payment');
        Route::get('my-order', 'ShopController@myOrder');

        Route::post('update-informasi-kontak', 'ProfileController@updateKontak');
        Route::post('update-shipping', 'ProfileController@updateShipping');
        Route::post('update-foto-profil', 'ProfileController@gantiFotoProfil');

        // AKSES ADMIN
        Route::post('update-contact-company','SettingContactController@update');
        Route::post('update-about-company', 'AboutController@update');
        
        Route::post('spanduks/create', 'SpandukController@newSpanduk');
        Route::post('spanduks/update/{id}', 'SpandukController@update');
        Route::delete('spanduks/delete/{id}','SpandukController@delete');
        Route::get('spanduks/trash','SpandukController@trash');
        Route::get('spanduks/restore/{id}','SpandukController@restore');
        Route::delete('spanduks/delete-permanent/{id}','SpandukController@deletePermanet');


        Route::get('categories/show-add-category','CategoryController@showAddCategory');
        Route::post('categories/create', 'CategoryController@create');
        Route::post('categories/update/{id}','CategoryController@update');
        Route::delete('categories/delete/{id}','CategoryController@delete');
        Route::get('categories/trash','CategoryController@trash');
        Route::get('categories/restore/{id}', 'CategoryController@restore');
        Route::delete('categories/delete-permanent/{id}', 'CategoryController@deletePermanent');

        Route::get('add-product','BookController@showProduct');
        Route::post('add-product/create','BookController@create');
        Route::delete('add-product/delete/{id}','BookController@delete');
        Route::get('add-product/trash','BookController@trash');
        Route::get('add-product/restore/{id}','BookController@restore');
	});
	
});