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




Auth::routes(['verify' => true]);
Route::middleware(['auth:api', 'verified'])->group(function () {
    // Comments
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Route::post('/client/create', 'ClientController@store');
    // Route::get('/client/edit/{id}', 'ClientController@edit');
    // Route::post('/client/update/{id}', 'ClientController@update');
    // Route::delete('/client/delete/{id}', 'ClientController@delete');
    //Route::get('/clients', 'ClientController@list');

});

Route::get('/clients', 'ClientController@list');

Route::get('/customers', 'CustomerController@list');