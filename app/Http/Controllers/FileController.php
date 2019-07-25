<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadRequest;
use App\File;

use App\Traits\ExcelTrait;



class FileController extends Controller
{
    //

    use ExcelTrait;
    public function index(){

        $files = File::all();
        return $files;
    }

    public function upload(){
        return view('file.upload');
    }

    public function uploadSubmit(UploadRequest $request){

        
        $folder = env('AWS_BUCKET_FOLDER');
    	$folder = empty($folder)? '' : $folder;
        
        //$path = $request->file('archivo')->store($folder, 's3');

        

        $excel = collect($this->read($request));
        /*
        * Creo una nueva collection con el articulo y la cantidad
        */
        $map = $excel->map(function($items){
            $data['articulo'] = $items['A'];
            $data['cantidad'] = $items['G'];
            return $data;
         });

         /*
         *  Agregar validaciones
         *      -> Articulos que existan
         *      ->  cantidades validas > 0
         * 
         */
         
         /*
            Si es valido guardo archivo
            Si es valido grabo archivo
         */

         /*
            Mostrar vista 
         */
         
         
         

        return $map;
    }

    
}
