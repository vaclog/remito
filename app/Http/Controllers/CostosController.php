<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class CostosController extends Controller
{
    //Controlador del modulo de costos

    public function CargaInicial()
    {
        $clientes = DB::table('clients')->select('id','razon_social')->orderByRaw('razon_social asc')->get();
        $tipoop=array(1=>'Nuevo',2=>'Devolución');

        return view('costos', compact('clientes','tipoop'));
    }

    #Carga los datos del cliente seleccionado
    Public function CargaCliente(Request $request)
    {
        $request->validate(['cbo_clientes'=>'required']);

        #Busco Razon Social
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("id=$request->cbo_clientes")->pluck('razon_social');
        #Cargo el nombre de las tarifas
        $DS_Tarifas = DB::table('cat_nombre_tarifas')->select('idtarifa','nombre')->orderByRaw('nombre asc')->get();

        #Query
        $DS_Costos=DB::table('vs_abm_costos')->select('*')->whereRaw("IdCliente=$request->cbo_clientes")->get();
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);
        #print_r($DS_Costos[1]);
        #dd($DS_Costos);
        $DS_CostosJS=json_encode($DS_Costos);
        return view('costosabm',compact('DS_CostosJS','DS_Tarifas'));
    }

    public function UpdateCreate(Request $request)
    {
        #Inserta, Actualiza o Elimina
        $DS_sp_costos = DB::select("call sp_costos(?,?,?,?,?,?,?)",
                                    [$request->idrec,$request->session()->get('IDCliente'),$request->cbo_tarifas,
                                    $request->txprecio,$request->cbo_activa,$request->accion,$request->controlupdate]);

        foreach($DS_sp_costos as $salida)
        {
            $dato=$salida->Salida;
        }

        $_Salida=array('Error'=>0,'Msg'=>$dato);
        echo json_encode($_Salida);
    }
}
