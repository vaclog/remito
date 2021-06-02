<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class PalletOutController extends Controller
{
    public function Formulario()
    {
        $clientes = DB::table('clients')->select('vkm_cliente_id as id','razon_social')->orderByRaw('razon_social asc')->get();
        //$tipoop=array(1=>'Nuevo',2=>'Devolución');
        return view('palletout', compact('clientes'));
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

        #$FdIng=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');
        #$FaIng=date_format(date_create_from_format('m/Y', $request->fecha_hasta), 'Y-m-d');

        $Fecha=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');

        $Temp=explode('-',$Fecha);
        $Anio=$Temp[0];
        $Mes=$Temp[1];


        #Le cambio el dia a las fechas para que quede siempre en 01, dado que se utiliza Mes y Año.
        /*$FdIngE=explode('-',$FdIng);
        $FdIng=$FdIngE[0].'-'.$FdIngE[1].'-01';

        $FaIngE=explode('-',$FaIng);
        $FaIng=$FaIngE[0].'-'.$FaIngE[1].'-01';*/

        $FechaLabelDesde=$request->fecha_desde;
        #$FechaLabelHasta=$request->fecha_hasta;

        #Verifico estado de la operacion
        $Es=DB::select("select fx_verifica_estado_operaciones(?,?,?,?) as Salida",[$request->cbo_clientes,$Anio,$Mes,2]);
        foreach($Es as $valor)
        {
            $Estado=$valor->Salida;
        }

        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("vkm_cliente_id=$request->cbo_clientes")->pluck('razon_social');
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);

        #Query
        $Remitos = DB::table('vs_salidas')->select('*')->whereRaw("year(fecha_alta)=$Anio and month(fecha_alta)=$Mes and cliente_id=$request->cbo_clientes")->get();
        $SumBultos=0;
        foreach($Remitos as $sumas)
        {
            $SumBultos+=$sumas->bultoremito;
        }

        $ID_Cliente=$request->cbo_clientes;

        #dd($Remitos);
        $DSRemitosJson=json_encode($Remitos);
        return view('palletoutgrid',compact('DSRemitosJson','FechaLabelDesde','SumBultos','ID_Cliente','Anio','Mes','Estado'));
    }

    public function SP_Palletout(Request $request)
    {
        $_Error=0;
        $_Msg='';
        $_CostoPIN=0;
        $_CostoPICK=0;

        try{
            #Llama al SP
            $DS_sp_palletout = DB::statement("CALL sp_palletout(?,?,?)", [ $request->idrec,
                                                                        $request->session()->get('IDCliente'),
                                                                        $request->valor]);


            // $DS_sp_palletout = DB::update( 'UPDATE vkm_salidas SET
            //                                         BultoRemito = ?
            //                                   WHERE id = ?
            //                                     AND cliente_id = ? ',
            // [
            //     $request->valor,
            //     $request->idrec,
            //     $request->session()->get('IDCliente')


            //  ]);
            $_Error=0;
        }catch(\Exception $e)
        {
            dd($e);
            $_Error=1;
        }

        $_Salida=array('Error'=>$_Error);
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

            $DS_PDF_Palletout = DB::table('vs_salidas')->select('*')->whereRaw("year(fecha_alta)=$Anio and month(fecha_alta)=$Mes and cliente_id=$Idcliente")->get();

            $SumBultos=0;
            foreach($DS_PDF_Palletout as $sumas)
            {
                $SumBultos+=$sumas->bultoremito;
            }

            $_ErrorResumen=0;
            $Periodo=$Mes.' // '.$Anio;

            $Cliente=$request->session()->get('RazonSocial');
            $NombrePDF='PalletOut_'.$Idcliente.'.pdf';

            #->setPaper('a4', 'landscape')
            $pdfr = PDF::loadView('PalletoutPDF', compact('DS_PDF_Palletout','Cliente','Periodo','SumBultos'));

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
