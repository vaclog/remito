<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Remito;
use App\RemitoArticulo;
use Auth;
use App\Traits\ExcelTrait;
use App\Traits\GraphTrait;
use Illuminate\Support\Facades\DB;
use \Mpdf\Mpdf;
use Carbon\Carbon;


use PDF;
use App\Client;


class RemitoController extends Controller
{
    //
    use ExcelTrait, GraphTrait;


    public function store(Request $request){
        $remito = null;
        set_time_limit(0);


        $cliente = Client::find($request->client_id);
        $referencia = substr($request->referencia, 3); // TODO: Solo guardar nuemero sin el OEP o OEI

        DB::transaction(function() use ($request, &$remito, $cliente, $referencia) {
            $remito = Remito::create([
                'sucursal' => $cliente->sucursal,
                'numero_remito' => $request->numero_remito,
                'fecha_remito' => $request->fecha_remito,
                'observaciones' => $request->observaciones,
                'referencia' => $referencia,

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
                /****
                 *  Para Orien se debe eliminar el String OEP o OEI del pedido
                 *
                 */
                if ($remito->client_id != 4){
                    $tipo = substr($item['pedido'], 0, 3);
                    $pedido = substr($item['pedido'], 3, strlen($item['pedido'])); // TODO: Solo guardar nuemero sin el OEP o OEI
                }
                else {
                    $tipo = substr($item['pedido'], 0, 4);
                    $pedido = substr($item['pedido'], 4, strlen($item['pedido'])); // TODO: Solo guardar nuemero sin el OEEL
                }



                $articulo = new RemitoArticulo([
                    'codigo' => $item['codigo'],
                    'descripcion' => $item['descripcion'],
                    'marca' => $item['marca'],
                    'cantidad' => $item['cantidad'],
                    'product_id' => $item['product_id'],
                    'remito_id' => $remito->id,
                    'ean13' => $item['ean13'],
                    'fecha_vencimiento' => ($item['fecha_vencimiento'])?Carbon::createFromFormat('d/m/Y', $item['fecha_vencimiento']):null,
                    'unidad_medida'=> $item['unidad_medida'],
                    'lote' => $item['lote'],
                    'referencia' => $pedido,

                    'disabled' => 0,
                    'audit_created_by' => Auth::user()->email,
                    'client_id' => $remito->client_id,
                    'tipo_nota_venta' => $tipo
                ]);

                $articulo->save();

            }
        }, 5);

        $interfaz = $this->interfaceRemitoOrien($remito);
        if(!$interfaz) die('Error de Interfaces');


        return response()->json( $remito);
    }

    public function show(Request $request, $id){

        $remito = Remito::where('id', $id)->with('articulos')->first();
        //return response()->json($remito);
        return view('show', compact('remito'));



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


        $pdf = PDF::loadView('templates.remito.orien', compact('data'));
        return $pdf->download('remito.pdf');
        //return view('templates.remito.orien', compact('data'));

       // Se deja de utilizar para utilizar el DOMPF
       //return $this->RemitoPrintManager($request, $remito);

       //return $this->test1($remito);

    }

    public function excel(Request $request){
        $remito = Remito::where('id', $request->id)
                ->with('customer', 'articulos', 'client')->first();
        $data = $remito;

        if ($remito->client_id == 2){
            return $this->toExcelOrien($data);
        } else if ( $remito->client_id == 4 ){
            return $this->toExcelELCA($data);
        }

    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user()->email;
        $remito = Remito::find($id);
        $remito->disabled = 1;


        $remito->audit_updated_by = $user;
        $remito->save();

        return back()->withInput();
        // return view('index', compact('remitos', 'clients', 'client_selected'))
        // ->with('i', ($request->input('page', 1) - 1) * 20);
    }



}
