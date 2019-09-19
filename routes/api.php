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
    

    


});

Route::post('/test', 'RemitoController@test');

Route::get('/clients', 'ClientController@list');

Route::get('/customers/{client_id}', 'CustomerController@list');

Route::get('/remito', 'RemitoController@detail');



Route::get('/remito/print', 'RemitoController@print');
Route::get('/remito/excel', 'RemitoController@excel');

