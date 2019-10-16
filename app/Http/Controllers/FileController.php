<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadRequest;
use App\File;
use App\Product;
use App\Remito;
use App\Customer;

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
                  "type"=> "Debe indicar un arhivo ",
                  "code"=> 500,
                  "error_subcode"=> 463,
                  "fbtrace_id"=> "error grave"
                ]
                ], 500);
        }
        if ($request->input('armado') == '' || !$request->input('armado') ){
            return response()->json( [
                "error"=> [
                  "message"=> "Debe indicar el numero de Armado de VALMIMIA",
                  "type"=> "Error grave",
                  "code"=> 500,
                  "error_subcode"=> 463,
                  "fbtrace_id"=> "error grave"
                ]
                ], 500);
        }

        $client_id = $request->input('client_id');
       
        $excel = collect($this->read($request));
        /*
        * Creo una nueva collection con el articulo y la cantidad
        */
        $customer_codigo_valkimia = null;
        $pedido = '';
        $map = $excel->map(function($items, $i) use (&$customer_codigo_valkimia, &$pedido) {
            
                $data['codigo'] = $items['F']; // Articulo
                $data['cantidad'] = $items['J'];   // Pickeada
                $data['descripcion'] = $items['H'];   // Pickeada
                $data['marca'] = 'no definida';
                $data['cliente_entidad_id'] = $items['C'];
                $customer_codigo_valkimia = $items['C'];
                
                if ( strpos($pedido, strval($items['E'])) !== false)
                        {}
                else{
                    $pedido = $pedido.((strlen($pedido) == 0)?$items['E']:', '.$items['E']);
                }
                
                $data['pedido'] = $items['E'];
                $data['cliente_nombre'] = $items['D'];
                $data['fecha_vencimiento'] = '';
                $data['lote'] = '';
                $data['product_id'] = null;
                $data['ean13'] = null;
                $data['unidad_medida'] = 'UN';

              

                return $data;
            
         });

      
        
        $customer = Customer::where('codigo_valkimia', $customer_codigo_valkimia)
                            ->where('disabled', 0)->first();
        
        $respuesta['numero_remito'] = $this->getNextRemito($client_id);
        $respuesta['pedido'] = $pedido;

        $respuesta['articulos'] = $map;
        $respuesta['customer'] = $customer;
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
