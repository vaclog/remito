<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Remito;
use App\RemitoArticulo;
use Auth;
use App\Traits\ExcelTrait;
use Illuminate\Support\Facades\DB;



class RemitoController extends Controller
{
    //
    use ExcelTrait;
    protected $sucursal = 4;

    public function store(Request $request){
        $remito = null;
        DB::transaction(function() use ($request, &$remito) {
            $remito = Remito::create([
                'sucursal' => $this->sucursal,
                'numero_remito' => $request->numero_remito,
                'fecha_remito' => $request->fecha_remito,
                'customer_id' => $request->customer['id'],
                'transporte' => $request->transportista['transporte'],
                'conductor' => $request->transportista['conductor'],
                'patente' => $request->transportista['patente'],
                'calle' => $request->customer['calle'],
                'localidad' => $request->customer['localidad'],
                'provincia' => $request->customer['provincia'],
                'disabled' => 0,
                'audit_created_by' => Auth::user()->email,
                'client_id' => $request->customer['client_id']

            ]);

            foreach($request->articulos as $item)
            {
                //dd($item);
                $articulo = new RemitoArticulo([
                    'codigo' => $item['articulo'],
                    'descripcion' => $item['descripcion'],
                    'marca' => $item['marca'],
                    'cantidad' => $item['cantidad'],
                    'product_id' => $item['product_id'],
                    'remito_id' => $remito->id,
                    'disabled' => 0,
                    'audit_created_by' => Auth::user()->email,
                    'client_id' => $remito->client_id
                ]);

                $articulo->save();
            }
        }, 5);
        
        
        return response()->json( $remito);
    }

    public function show(Request $request, $id){

        $remito = Remito::where('id', $id)->with('articulos')->first();

        return view('show', compact('remito'));

        //return response()->json($remito);

    }

    public function detail(Request $request){
        $remito = Remito::where('id', $request->id)->with('articulos', 'customer')->first();
        return response()->json($remito);
    }

    public function create(Request $request){

        return view ('home');
    }

    public function print(Request $request){

       
        return $this->RemitoPrint($request);
       
    }
}
