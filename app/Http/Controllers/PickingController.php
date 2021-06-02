<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class PickingController extends Controller
{
    //Formulario de filtro de fechas
    public function Formulario()
    {
        $clientes = DB::table('clients')->select('vkm_cliente_id as id','razon_social')->orderByRaw('razon_social asc')->get();
        $tipoop=array(1=>'Nuevo',2=>'Devolución');
        return view('picking', compact('clientes','tipoop'));
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

        /*$request->validate([
            'cbo_clientes'=>'required',
            'fecha_desde'=>'required',
            'fecha_hasta'=>'required'
        ]);*/

        $request->validate([
            'cbo_clientes'=>'required',
            'fecha_desde'=>'required'
        ]);

        #$FdIng=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');
        #$FaIng=date_format(date_create_from_format('m/Y', $request->fecha_hasta), 'Y-m-d');

        $Fecha=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');

        $Temp=explode('-',$Fecha);
        $Anio=$Temp[0];
        $Mes=$Temp[1];

        /*$FdIng=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');
        $FaIng=date_format(date_create_from_format('m/Y', $request->fecha_hasta), 'Y-m-d');

        #Le cambio el dia a las fechas para que quede siempre en 01, dado que se utiliza Mes y Año.
        $FdIngE=explode('-',$FdIng);
        $FdIng=$FdIngE[0].'-'.$FdIngE[1].'-01';

        $FaIngE=explode('-',$FaIng);
        $FaIng=$FaIngE[0].'-'.$FaIngE[1].'-01';*/

        $FechaLabelDesde=$request->fecha_desde;
        #$FechaLabelHasta=$request->fecha_hasta;

        #Verifico estado de la operacion
        $Es=DB::select("select fx_verifica_estado_operaciones(?,?,?,?) as Salida",[$request->cbo_clientes,$Anio,$Mes,3]);
        foreach($Es as $valor)
        {
            $Estado=$valor->Salida;
        }

        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("vkm_cliente_id=$request->cbo_clientes")->pluck('razon_social');
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);

        $ID_Cliente=$request->cbo_clientes;

        #Query
        $DSPicking = DB::select("call sp_ls_picking(?,?,?)",[$request->session()->get('IDCliente'),$Anio,$Mes]);

        #Sumatoria para la parte derecha
        $SumTotUnidPickeadas=0;
        $SumTotCajasDecimales=0;
        $SumTotCajas=0;
        $SumUnidades=0;
        $SumTotPickeadas=0;

        foreach($DSPicking as $sumas)
        {
            $SumTotUnidPickeadas+=$sumas->cant_preparada_unidad;
            $SumTotCajasDecimales+=$sumas->ColCajaDecimales;
            $SumTotCajas+=$sumas->ColCajas;
            $SumUnidades+=$sumas->ColUnidades;
            $SumTotPickeadas+=$sumas->ColPickeadas;
        }


        #dd($Remitos);
        $DSPickingJson=json_encode($DSPicking);
        return view('pickinggrid',compact('DSPickingJson','FechaLabelDesde','SumTotUnidPickeadas',
                            'SumTotCajasDecimales','SumTotCajas','SumUnidades','SumTotPickeadas','Anio','Mes','ID_Cliente','Estado'));
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

            $DS_PDF_PICKINGDEV = DB::select("call sp_ls_picking(?,?,?)",[$request->session()->get('IDCliente'),$Anio,$Mes]);

            #Sumatoria para la parte derecha
            $SumTotUnidPickeadas=0;
            $SumTotCajasDecimales=0;
            $SumTotCajas=0;
            $SumUnidades=0;
            $SumTotPickeadas=0;

            foreach($DS_PDF_PICKINGDEV as $sumas)
            {
                $SumTotUnidPickeadas+=$sumas->cant_preparada_unidad;
                $SumTotCajasDecimales+=$sumas->ColCajaDecimales;
                $SumTotCajas+=$sumas->ColCajas;
                $SumUnidades+=$sumas->ColUnidades;
                $SumTotPickeadas+=$sumas->ColPickeadas;
            }

            $_ErrorResumen=0;
            $Periodo=$Mes.' // '.$Anio;

            $Cliente=$request->session()->get('RazonSocial');
            $NombrePDF='PickingDev_'.$Idcliente.'.pdf';

            #->setPaper('a4', 'landscape')
            $pdfr = PDF::loadView('PickingPDF', compact('DS_PDF_PICKINGDEV','Cliente','Periodo','SumTotUnidPickeadas','SumTotCajasDecimales',
            'SumTotCajas','SumUnidades','SumTotPickeadas'))->setPaper('a4', 'landscape');

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
