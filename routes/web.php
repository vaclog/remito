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

use Krizalys\Onedrive\Onedrive;
use Illuminate\Http\Request;

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
    Route::post('/remitos/store', 'RemitoController@store');

    

    Route::resource('remitos', 'RemitoController');


    Route::resource('files', 'FileController', ['only' => ['store', 'destroy']]);

    Route::get('/onedrive/test', function(){

        $client = Onedrive::client(env('ONEDRIVE_CLIENT_ID'));

        $url = $client->getLogInUrl([
            'files.read',
            'files.read.all',
            'files.readwrite',
            'files.readwrite.all',
            'offline_access',
        ], env('ONEDRIVE_URI'));

        session(['onedrive.client.state' => $client->getState()]);
        
        // Redirect the user to the log in URL.
        

        return redirect( $url)->with('status', 302);
    });


    Route::get('/onedrive/auth', function(Request $request){

        if (!session()->has('onedrive.client.state')) {
            throw new \Exception('onedrive.client.state undefined in $_SESSION');
        }
        
        $code = $request->code;
        $client = Onedrive::client(
            env('ONEDRIVE_CLIENT_ID'),
            [
                // Restore the previous state while instantiating this client to proceed
                // in obtaining an access token.
                'state' => session('onedrive.client.state'),
            ]
        );
        

        $token = $client->obtainAccessToken(env('ONEDRIVE_CLIENT_SECRET'), $code);

        session(['onedrive.client.state' => $client->getState()]);

        dd($client->getDrives());

        // if (!array_key_exists('code', $_GET)) {
        //     throw new \Exception('code undefined in $_GET');
        // }

        

        
    });

    Route::get('/signin', 'AuthController@signin');
    Route::get('/authorize', 'AuthController@gettoken');
    Route::get('/mail', 'OutlookController@mail')->name('mail');
    Route::get('/root', 'OutlookController@root')->name('root');
    Route::get('/children', 'OutlookController@children')->name('children');
    Route::get('/items', 'OutlookController@items')->name('items');



}
);