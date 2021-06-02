<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class ArticulosController extends Controller
{
    //Formulario de filtro de fechas
    public function Formulario()
    {
        $clientes = DB::table('clients')->select('vkm_cliente_id as id','razon_social')->orderByRaw('razon_social asc')->get();
        return view('articulos', compact('clientes'));
    }

    public function ValidarFormulario(Request $request)
    {
        $request->validate(['cbo_clientes'=>'required']);

        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("vkm_cliente_id=$request->cbo_clientes")->pluck('razon_social');
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);
        $ID_Cliente=$request->cbo_clientes;

        #Query
        $DSListaArticulos = DB::table('vs_articuloslista')->select('*')->whereRaw("cliente_id=$request->cbo_clientes")->paginate(15);
        //return view('articuloslista',compact('DSListaArticulos'));
        return redirect()->route('articuloslista',compact('DSListaArticulos'));
    }

    public function ArticulosLista(Request $request)
    {
        $idc=$request->session()->get('IDCliente');
        #Query
        $DSListaArticulos = DB::table('vs_articuloslista')->select('*')->whereRaw("cliente_id=".$request->session()->get('IDCliente'))->paginate(15);
        return view('articuloslista',compact('DSListaArticulos','idc'));
    }


    public function SP_UP(Request $request)
    {
        $_Error=0;

        try{
             #Llama al SP
            DB::select("call SP_ProductosUpdate(?,?,?)",[$request->Cliente,$request->idp,$request->valor]);
            $_Error=0;
        }catch(\Exception $e)
        {
            $_Error=1;
        }

        return response()->json(['Error' => $_Error], 200);
    }
}
