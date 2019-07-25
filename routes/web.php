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

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth:web']], function() {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/admin', 'AdminController@index');

    Route::resource('/admin/roles','RoleController');

    Route::resource('/admin/clients', 'ClientController');

    Route::resource('/admin/products', 'ProductController');

    Route::resource('/admin/customers', 'CustomerController');



    // Route::get('/admin/clients', function () {
    //     return view('admin.clients.client');
    //   })->where('any', '.*');

    Route::get('/files', 'FileController@index');
    Route::get('/upload', 'FileController@upload');

    Route::post('/upload', 'FileController@uploadSubmit');


    Route::resource('files', 'FileController', ['only' => ['store', 'destroy']]);
}
);