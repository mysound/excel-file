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
    return view('welcome', [
    	'products' => App\Product::all()
    ]);
});

Route::get('/products', 'ProductsController@index')->name('products');
Route::get('/export', 'ProductsController@export')->name('export');
Route::post('/import', 'ProductsController@import')->name('import');
Route::get('/addimage', 'ProductsController@addimage')->name('addimage');