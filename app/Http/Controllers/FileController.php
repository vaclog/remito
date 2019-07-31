<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadRequest;
use App\File;
use App\Product;

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

        
        //return $this->read($request);
        $excel = collect($this->read($request));
        /*
        * Creo una nueva collection con el articulo y la cantidad
        */
        $map = $excel->map(function($items, $i){
            
                $data['articulo'] = $items['A'];
                $data['cantidad'] = $items['G'];
                return $data;
            
         });

         $map = $map->sortBy('articulo');

         $wherein = $map->map(function($items, $i){
             $data[$i] = $items['articulo'];
             return $data;
         });

         //return $wherein;
         //dd($map);
         
         /*for ($i =1; $i < $map->count(); $i++){
            $wherein = $wherein."'".$map[$i]['articulo']."',";

         }*/

         
         //$wherein =['CACA', 'PEPE'];
         $product = Product::whereIn('codigo',$wherein)
                    ->orderBy('codigo')
                    ->get();


        $matchs = $this->MatchProducts($map, $product);

        return $matchs;
         return $product;
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

    public function MatchProducts($map, $product_list){

        /*for($i = 1; $i < $map->count(); $i++){
            $map[$i]['articulo']);
        }*/

        $matchs = $map->map(function ($items, $i) use ($product_list) {
            $data['articulo'] = $items['articulo'];
            $data['cantidad'] = $items['cantidad'];

            $match = $product_list->firstWhere('codigo',$data['articulo']);
            if ($match){
                $data['descripcion'] = $match->descripcion;
                $data['marca'] = $match->marca;
            }else {
                $data['descripcion'] = 'No encontrado';
                $data['marca'] = 'No encontrado';
            }
            return $data;
        });

        return $matchs;
    }
}
