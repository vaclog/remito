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

Route::group(
    ['middleware' => ['auth:web']],
    function () {

        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('/admin', 'AdminController@index');

        Route::resource('/admin/roles', 'RoleController');

        Route::resource('/admin/clients', 'ClientController');

        Route::resource('/admin/products', 'ProductController');

        Route::resource('/admin/customers', 'CustomerController');



        // Route::get('/admin/clients', function () {
        //     return view('admin.clients.client');
        //   })->where('any', '.*');

        Route::get('/files', 'FileController@index');
        Route::get('/upload', 'FileController@upload');

        Route::post('/upload', 'FileController@uploadSubmit');
        //Route::post('/remitos/store', 'RemitoController@store');
        //Route::delete('remitos/{id}', 'RemitoController@destroy');



        Route::resource('remitos', 'RemitoController');


       // Route::post('remitos/delete/{id}', 'RemitoController@destroy')->name('deleteRemito');



        Route::any('/search', 'HomeController@index');


        Route::resource('files', 'FileController', ['only' => ['store', 'destroy']]);

        Route::get('/plantillabase', function () {
            return view('plantillabase');
        })->name('planillabase');

#Articulos
Route::get('articuloslista','ArticulosController@ArticulosLista')->name('articuloslista');
Route::get('articulos','ArticulosController@Formulario')->name('articulos');
Route::post('articulos','ArticulosController@ValidarFormulario')->name('articulos.filtroform');
Route::post('articulosup','ArticulosController@SP_UP')->name('articulosup');


#Palets-In llamado a su controlador para el formulario inicial
Route::get('palletin','Palletincontroller@Formulario')->name('palletin');

#Ruta para el envio del formulario de Palletin (formulario de seleccion de clinete)
Route::post('palletin','Palletincontroller@ValidarFormulario')->name('palletin.filtroform');

#Costos por clientes
Route::get('costos','CostosController@CargaInicial')->name('costos');
Route::post('costos','CostosController@CargaCliente')->name('Costos.CargaCliente');
Route::post('ajcostos','CostosController@UpdateCreate')->name('costosupdate');

#Palletin AJAX
Route::post('ajpalletin','Palletincontroller@SP_Palletin')->name('palletinupdate');

#Pallet-OUT
Route::get('palletout','PalletOutController@Formulario')->name('palletout');
Route::post('palletout','PalletOutController@ValidarFormulario')->name('palletout.filtroform');
Route::post('ajpalletout','PalletOutController@SP_Palletout')->name('palletoutupdate');

#Picking
Route::get('piking','PickingController@Formulario')->name('picking');
Route::post('picking','PickingController@ValidarFormulario')->name('picking.filtroform');

#Almacenaje
Route::get('almacenaje','AlmacenajeController@Formulario')->name('almacenaje');
Route::post('almacenaje','AlmacenajeController@ValidarFormulario')->name('almacenaje.filtroform');
#...AJAX
Route::post('ajalmacenaje','AlmacenajeController@SP_AlmacenajeGrabar')->name('ajax.almacenaje');

#Resumen
Route::get('resumen','resumenController@Formulario')->name('resumen');
Route::post('resumen','resumenController@ValidarFormulario')->name('resumen.filtroform');

#...Ajax

#*************************************************************************************************************************************************
Route::post('ajresumen','resumenController@SP_Grabar')->name('ajax.GrabaResumen');
Route::get('resumen/excel','resumenController@ExcelExport')->name('ajax.pdfresumen');

#...PDF
Route::post('palletinpdf','Palletincontroller@GeneraPDF')->name('PDFPalletin');
Route::post('palletoutpdf','PalletOutController@GeneraPDF')->name('PDFPalletout');
Route::post('pickingdevpdf','PickingController@GeneraPDF')->name('PDFPickingdev');
Route::post('almacenajepdf','AlmacenajeController@GeneraPDF')->name('PDFAlmacenaje');
    }
);


