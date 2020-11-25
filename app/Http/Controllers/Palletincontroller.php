<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class Palletincontroller extends Controller
{
    //Inicio
    public function Formulario()
    {
        $clientes = DB::table('clients')->where('disabled', 0)->select('id','razon_social')->orderByRaw('razon_social asc')->get();
        $tipoop=array(1=>'Nuevo',2=>'Devolución');
        return view('palletin', compact('clientes','tipoop'));
    }

    public function ValidarFormulario(Request $request)
    {
        /*
        $request->validate([
            'cbo_clientes'=>'required',
            'fecha_desde'=>'required',
            'fecha_hasta'=>'required'
        ]);
        */

        $request->validate([
            'cbo_clientes'=>'required',
            'fecha_desde'=>'required'
        ]);

        $Fecha=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');
        //$FaIng=date_format(date_create_from_format('m/Y', $request->fecha_hasta), 'Y-m-d');

        #Le cambio el dia a las fechas para que quede siempre en 01, dado que se utiliza Mes y Año.
        $Temp=explode('-',$Fecha);
        $Anio=$Temp[0];
        $Mes=$Temp[1];

        //$FaIngE=explode('-',$FaIng);
        //$FaIng=$FaIngE[0].'-'.$FaIngE[1].'-01';

        $FechaLabelDesde=$request->fecha_desde;
        //$FechaLabelHasta=$request->fecha_hasta;

        #Verifico estado de la operacion
        $Es=DB::select("select fx_verifica_estado_operaciones(?,?,?,?) as Salida",[$request->cbo_clientes,$Anio,$Mes,1]);
        foreach($Es as $valor)
        {
            $Estado=$valor->Salida;
        }

        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("id=$request->cbo_clientes")->pluck('razon_social');
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);

        #Verifico si el cliente tiene tarifas para utilizar en los calculos, este caso de Plletin y PickingDev
        $Tarifas = DB::select("select fx_check_tarifas_activas(?) as control",[$request->cbo_clientes]);

        #Query
        $Remitos = DB::table('vs_ingresos')->select('*')->whereRaw("year(fecha_alta)=$Anio and month(fecha_alta)=$Mes and cliente_id=$request->cbo_clientes")->get();

        $ID_Cliente=$request->cbo_clientes;

        #Sumatoria
        $SumCant=0;
        $SumPalletin=0;
        $SumPrecio=0;
        $SumPicking=0;

        foreach($Remitos as $sumas)
        {
            $SumCant+=$sumas->cant_recibida_unidad;
            $SumPalletin+=$sumas->CantidadPalletIN;
            $SumPrecio+=$sumas->PrecioPalletIN;
            $SumPicking+=$sumas->PickingDev;
        }


        #dd($Remitos);
        $DSRemitosJson=json_encode($Remitos);
        return view('palletingrid',compact('DSRemitosJson','Tarifas','Estado','FechaLabelDesde','SumCant','SumPalletin','SumPrecio','SumPicking','Anio','Mes','ID_Cliente'));
    }

    public function SP_Palletin(Request $request)
    {
        $_Error=0;
        $_Msg='';
        $_CostoPIN=0;
        $_CostoPICK=0;

        try{
            #Llama al SP
            $DS_sp_costos = DB::select("call sp_palletin(?,?,?,?)",[$request->idrec,$request->session()->get('IDCliente'),$request->valor,$request->tipo]);
            $_Error=0;
            foreach($DS_sp_costos as $salida)
            {
                $_CostoPIN=$salida->CostoPIN;
                $_CostoPICK=$salida->PickDev;
            }

        }catch(\Exception $e)
        {
            $_Error=1;
        }

        $_Salida=array('Error'=>$_Error,"Cpin"=>$_CostoPIN,"Cpkd"=>$_CostoPICK);
        echo json_encode($_Salida);
    }

    public function GeneraPDF(Request $request)
    {

        $_ErrorResumen=0;
        $_ErrMsg='';
        $_Path='';


        try{

            #Datos de items extras
            $Anio=$request->Anio;
            $Mes=$request->Mes;
            $Idcliente=$request->Cliente;

            $DS_PDF_Palletin = DB::table('vs_ingresos')->select('*')->whereRaw("year(fecha_alta)=$Anio and month(fecha_alta)=$Mes and cliente_id=$Idcliente")->get();

            #Sumatoria
            $SumCant=0;
            $SumPalletin=0;
            $SumPrecio=0;
            $SumPicking=0;

            foreach($DS_PDF_Palletin as $sumas)
            {
                $SumCant+=$sumas->cant_recibida_unidad;
                $SumPalletin+=$sumas->CantidadPalletIN;
                $SumPrecio+=$sumas->PrecioPalletIN;
                $SumPicking+=$sumas->PickingDev;
            }


            $_ErrorResumen=0;
            $Periodo=$Mes.' // '.$Anio;

            $Cliente=$request->session()->get('RazonSocial');
            $NombrePDF='PalletIn_'.$Idcliente.'.pdf';

            $pdfr = PDF::loadView('PalletinPDF', compact('DS_PDF_Palletin','Cliente','Periodo','SumCant','SumPalletin','SumPrecio','SumPicking'))->setPaper('a4', 'landscape');

            Storage::disk('public')->delete('pdf/'.$NombrePDF);

            $pdfr->save('pdf/'.$NombrePDF);
            $_Path='pdf/'.$NombrePDF;

            $_ErrMsg='Ok';

        }catch(\Exception $e){
            $_ErrMsg = $e->getMessage();
            $_ErrorResumen=1;
        }

        $_Salida=array('Error'=>$_ErrorResumen,"Msg"=>$_ErrMsg,"Path"=>$_Path);
        echo json_encode($_Salida);
    }

}
