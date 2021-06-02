<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class AlmacenajeController extends Controller
{
    //Formulario de filtro de fechas
    public function Formulario()
    {
        $clientes = DB::table('clients')->select('vkm_cliente_id as id','razon_social')->orderByRaw('razon_social asc')->get();
        return view('almacenaje', compact('clientes'));
    }

    public function ValidarFormulario(Request $request)
    {
        $request->validate([
            'cbo_clientes'=>'required',
            'fecha_desde'=>'required'
        ]);

        $FdIng=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');
        $FechaVistaARR=explode('-',$FdIng);
        $FechaVista=$FechaVistaARR[1].'/'.$FechaVistaARR[0];
        $Anio=$FechaVistaARR[0];
        $Mes=$FechaVistaARR[1];


        #Antes de continuar ejecutamos el SP para generar los registros en caso que no existan
        try{
            #Llama al SP
            $DS_sp_crea_fechas_almacenaje = DB::select("call sp_crea_fechas_almacenaje(?,?,?)",[$request->cbo_clientes,$Mes,$Anio]);
            $_Error=0;
        }catch(\Exception $e)
        {
            $_Error=1;
        }


        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("vkm_cliente_id=$request->cbo_clientes")->pluck('razon_social');
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);
        $ID_Cliente=$request->cbo_clientes;

        #Query
        #$DSAlmacenaje = DB::table('vs_almacenaje')->select('*')->whereRaw("mes=month('$FdIng') and anio=year('$FdIng') and cliente_id=$request->cbo_clientes")->get();
        $DSAlmacenaje = DB::table('vs_almacenaje')->select('*')->whereRaw("mes=$Mes and anio=$Anio and cliente_id=$request->cbo_clientes")->get();

        #Sumatoria
        $SumStockMaximo=0;
        $StockMaximo=0;
        $PrecioCostos=0;
        foreach($DSAlmacenaje as $sumas)
        {
            $PrecioCostos=$sumas->Precio;
            #...Busco el valor maximo de TotalAlmacenaje
            if($sumas->TotalAlmacenaje>$StockMaximo) $StockMaximo=$sumas->TotalAlmacenaje;
        }

        #....Otros calculos
        $Excedente=($StockMaximo-$PrecioCostos);

        #Verifico estado de la operacion
        $Es=DB::select("select fx_verifica_estado_operaciones(?,?,?,?) as Salida",[$request->cbo_clientes,$Anio,$Mes,4]);
        foreach($Es as $valor)
        {
            $Estado=$valor->Salida;
        }

        #dd($Remitos);
        $DSAlmacenajeJson=json_encode($DSAlmacenaje);
        return view('almacenajegrid',compact('DSAlmacenajeJson','FechaVista','_Error','StockMaximo','Excedente','PrecioCostos','ID_Cliente','Anio','Mes','Estado'));
    }

    public function SP_AlmacenajeGrabar(Request $request)
    {
        $_Error=0;
        $_GranTotal=0;
        $_StockMaximo=0;
        $_StockBase=0;
        $_Excedente=0;

        try{
            #Llama al SP
            $DS_AlmacenajeGrabar = DB::select("call SP_AlmacenajeGrabar(?,?,?)",[$request->idrec,
            $request->col,
            $request->valor]);
            $_Error=0;
            foreach($DS_AlmacenajeGrabar as $salida)
            {
                $_GranTotal=$salida->Total;
                $_StockMaximo=$salida->StockMaximo;
                $_StockBase=$salida->StockBase;
                $_Excedente=$salida->Excedente;
            }
        }catch(\Exception $e)
        {
            $_Error=1;
        }

        $_Salida=array('Error'=>$_Error,"Total"=>$_GranTotal,"StockMaximo"=>$_StockMaximo,"StockBase"=>$_StockBase,"Excedente"=>$_Excedente);
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

            $DS_PDF_ALMACENAJE = DB::table('vs_almacenaje')->select('*')->whereRaw("mes=$Mes and anio=$Anio and cliente_id=$Idcliente")->get();

            #Sumatoria
            $SumStockMaximo=0;
            $StockMaximo=0;
            foreach($DS_PDF_ALMACENAJE as $sumas)
            {
                $PrecioCostos=$sumas->Precio;
                #...Busco el valor maximo de TotalAlmacenaje
                if($sumas->TotalAlmacenaje>$StockMaximo) $StockMaximo=$sumas->TotalAlmacenaje;
            }

            $Excedente=($StockMaximo-$PrecioCostos);

            $_ErrorResumen=0;
            $Periodo=$Mes.' // '.$Anio;

            $Cliente=$request->session()->get('RazonSocial');
            $NombrePDF='Almacenaje_'.$Idcliente.'.pdf';

            #->setPaper('a4', 'landscape')
            $pdfr = PDF::loadView('AlmacenajePDF', compact('DS_PDF_ALMACENAJE','Cliente','Periodo','StockMaximo','Excedente','PrecioCostos'))->setPaper('a4', 'landscape');

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
