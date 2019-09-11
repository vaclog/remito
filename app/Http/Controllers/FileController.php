<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadRequest;
use App\File;
use App\Product;
use App\Remito;

use App\Traits\ExcelTrait;
use Carbon\Carbon;



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
        //dd(collect($request));
        if (!$request->hasFile('archivo')){
            return response()->json( [
                "error"=> [
                  "message"=> "No se selecciono el archivo",
                  "type"=> "OAuthException",
                  "code"=> 500,
                  "error_subcode"=> 463,
                  "fbtrace_id"=> "H2il2t5bn4e"
                ]
                ], 500);
        }
        $client_id = $request->input('client_id');
       
        $excel = collect($this->read($request));
        /*
        * Creo una nueva collection con el articulo y la cantidad
        */
        $map = $excel->map(function($items, $i){
            
                $data['codigo'] = $items['A']; // Articulo
                $data['cantidad'] = $items['G'];   // Pickeada

                
                $fecha_DD_MM_YYYY = date_format(Carbon::createFromFormat('m/d/Y', $items['I']), 'd/m/Y');
                $data['fecha_vencimiento'] = $fecha_DD_MM_YYYY; // 'DD/MM/YYYY'

                $data['lote'] = $items['J'];

                return $data;
            
         });

         $map = $map->sortBy('codigo');

         $wherein = $map->map(function($items, $i){
             $data[$i] = $items['codigo'];
             return $data;
         });

         
         $product = Product::whereIn('codigo',$wherein)
                    ->orderBy('codigo')
                    ->where('client_id', $client_id)
                    ->get();


        $matchs = $this->MatchProducts($map, $product);


        
        
        $respuesta['numero_remito'] = $this->getNextRemito($client_id);
        $respuesta['articulos'] = $matchs;
        return response()->json($respuesta);
        
        
    }

    public function getNextRemito($client_id){
        return (Remito::where('disabled' , 0)
                ->where('client_id', $client_id)
                ->max('numero_remito') + 1);

    }

    public function MatchProducts($map, $product_list){

        

        $matchs = $map->map(function ($items, $i) use ($product_list) {
            $data['codigo'] = $items['codigo'];
            $data['cantidad'] = $items['cantidad'];
            $data['fecha_vencimiento'] = $items['fecha_vencimiento'];
            $data['lote'] = $items['lote'];

            $match = $product_list->firstWhere('codigo',$data['codigo']);
            if ($match){
                $data['descripcion'] = $match->descripcion;
                $data['marca'] = $match->marca;
                $data['product_id'] = $match->id;
                $data['unidad_medida'] = $match->unidad_medida;
                $data['ean13'] = $match->ean13;
            }else {
                $data['descripcion'] = 'No encontrado';
                $data['marca'] = 'No encontrado';
                $data['product_id'] = null;
                $data['unidad_medida'] = '-';
                $data['ean13'] = '-';
            }
            return $data;
        });

        return $matchs;
    }
}
