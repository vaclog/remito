<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Remito;
use App\RemitoArticulo;
use Auth;
use App\Traits\ExcelTrait;
use Illuminate\Support\Facades\DB;
use \Mpdf\Mpdf;
use Carbon\Carbon;


use PDF;
use App\Client;


class RemitoController extends Controller
{
    //
    use ExcelTrait;
    

    public function store(Request $request){
        $remito = null;

        $cliente = Client::find($request->client_id);
        DB::transaction(function() use ($request, &$remito, $cliente) {
            $remito = Remito::create([
                'sucursal' => $cliente->sucursal,
                'numero_remito' => $request->numero_remito,
                'fecha_remito' => $request->fecha_remito,
                'observaciones' => $request->observaciones,
                'cai' => $cliente->cai,
                'cai_vencimiento' => $cliente->cai_vencimiento,
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
                    'ean13' => $item['ean13'],
                    'fecha_vencimiento' => ($item['fecha_vencimiento'])?Carbon::createFromFormat('d/m/Y', $item['fecha_vencimiento']):null,
                    'unidad_medida'=> $item['unidad_medida'],
                    'lote' => $item['lote'],
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
        
        $client_id = $request->input('client_id');
        $client = Client::find($client_id);
        return view ('home', compact('client'));
    }

    public function print(Request $request){
        set_time_limit(0);

       $remito = Remito::where('id', $request->id)
                ->with('customer', 'articulos', 'client')->first();
        $data = $remito;

       
        //return $remito;
        $pdf = PDF::loadView('templates.remito.orien', compact('data'));
        return $pdf->stream('remito.pdf');
        //return view('templates.remito.orien', compact('data'));

       // Se deja de utilizar para utilizar el DOMPF
       //return $this->RemitoPrintManager($request, $remito);

       //return $this->test1($remito);
       
    }

    public function print2 (Request $request){
        
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<div class="row"><div class="col-6"><h1 class="text-center">Hello world! </h1></div><div class="col-6"><h1 class="text-center">Hello world! 22 </h1></div></div>');
        
        $mpdf->Output();
    }
}
