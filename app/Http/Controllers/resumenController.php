<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;

class resumenController extends Controller
{
    public function Formulario()
    {
        $clientes = DB::table('clients')->select('id','razon_social')
                    ->where('disabled', 0)
                    ->orderByRaw('razon_social asc')->get();
        $tipoop=array(1=>'Nuevo',2=>'Devolución');
        return view('resumen', compact('clientes','tipoop'));
    }

    public function ValidarFormulario(Request $request)
    {
        $request->validate([
            'cbo_clientes'=>'required',
            'fecha_resumen'=>'required'
        ]);

        #$FdIng=date_format(date_create_from_format('m/Y', $request->fecha_desde), 'Y-m-d');

        $F_Mes=date_format(date_create_from_format('m/Y', $request->fecha_resumen), 'Y-m-d');
        $F_Anio=date_format(date_create_from_format('m/Y', $request->fecha_resumen), 'Y-m-d');

        #Tomo el mes
        $FM=explode('-',$F_Mes);
        $F_Mes=$FM[1];

        #Tomo el año
        $FA=explode('-',$F_Anio);
        $F_Anio=$FA[0];

        $_DatosFecha=$F_Mes.' / '.$F_Anio;

        #Verifico estado de la operacion
        $Es=DB::select("select fx_verifica_estado_operaciones(?,?,?,?) as Salida",[$request->cbo_clientes,$F_Anio,$F_Mes,5]);
        foreach($Es as $valor)
        {
            $Estado=$valor->Salida;
        }

        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("id=$request->cbo_clientes")->pluck('razon_social');
        $request->session()->put('RazonSocial', $DS_RazonSocial[0]);
        $request->session()->put('IDCliente', $request->cbo_clientes);


        #Valido los datos
        #$DS_Items=null;

        try{
            #Llama al SP de validacion
            $DS_sp_resumen_previo = DB::select("call sp_resumen_previo(?,?,?)",[$request->cbo_clientes,$F_Mes,$F_Anio]);
            $_ErrorPI=0;$_ErrorPO=0;$_ErrorPC=0;$_ErrorAL=0;$_Error=1;$_IdEncabezado=0;$_Editable=0;
            foreach($DS_sp_resumen_previo as $salida)
            {
                $_ErrorPI=$salida->ErrPI;
                $_ErrorPO=$salida->ErrPO;
                $_ErrorPC=$salida->ErrPC;
                $_ErrorAL=$salida->ErrAL;
                $_IdEncabezado=$salida->IdEnc;
            }

            $_Editable=$_ErrorPI+$_ErrorPO+$_ErrorPC+$_ErrorAL;
            $request->session()->put('IdEncabezado', $_IdEncabezado);
            $request->session()->put('Periodo', $_DatosFecha);

            #Si todo salio bien, cargo los items extras para q puedan editarlos
            $DS_Items = DB::table('vs_resumen_items_extras')->select('recid','Id_Encabezado','Descripcion','Importe')->whereRaw("Id_Encabezado=$_IdEncabezado")->get();

        }catch(\Exception $e)
        {
            $_Error=1;
        }


        #dd($Remitos);
        $DSItemsJson=json_encode($DS_Items);
        return view('resumenGrid',compact('DSItemsJson','_ErrorPI','_ErrorPO','_ErrorPC','_ErrorAL','_Error','_DatosFecha','_Editable','Estado'));

    }

    public function SP_Grabar(Request $request)
    {
        $_Error=0;

        try{
            #Llama al SP
            $DS_sp_items = DB::select("call sp_actualiza_items_resumen(?,?,?,?)",[$request->idrec,$request->Desc,$request->Imp,$request->ColIdx]);
            $_Error=0;
        }catch(\Exception $e)
        {
            $_Error=1;
        }

        $_Salida=array('Error'=>$_Error);
        echo json_encode($_Salida);
    }

    public function GeneraPDF(Request $request)
    {
        $_ErrorResumen=0;
        $_ErrMsg='';
        $sumTotalConIva=0;
        $sumSubTotal=0;
        $TotIva=0;

        try{


            if($request->Accion==2)
            {
                $IdEnc=$request->session()->get('IdEncabezado');
                $DS_sp_cierra_resumen = DB::select("call sp_cierra_resumen($IdEnc)");
                $_ErrorResumen=0;
            }

            #Datos calculados
            $DS_Resumen = DB::table('vs_resumen_pdf')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado'))->get();
            $_ErrorResumen=0;

            #Sumatorias
            foreach($DS_Resumen as $salida)
            {
                $sumTotalConIva+=$salida->PrecioConIva;
                $sumSubTotal+=$salida->Precio;
            }

            #Tomo valor IVA
            $DS_IVA = DB::table('vkm_valor_iva')->select('Iva')->pluck('Iva');
            $IVA=$DS_IVA[0];

            #Datos de items extras
            $DS_Extras = DB::table('vs_resumen_items_extras')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado'))->get();
            #Sumatorias
            foreach($DS_Extras as $salida)
            {
                $sumTotalConIva+=round($salida->TotIva,2);
                $sumSubTotal+=round($salida->Importe,2);
            }

            $TotIva=round((($sumSubTotal*$IVA)/100),2);

            if($request->Accion==1)
            {
                $TextResumen='Prefacturacion Previo';
            }else{
                $TextResumen='Prefacturacion Final';
            }

            //$DS_Resumen = DB::table('vs_resumen_pdf')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado'))->get();

            $_ErrorResumen=0;
            $Cliente=$request->session()->get('RazonSocial');
            $Periodo=$request->session()->get('Periodo');
            $NombrePDF=$request->session()->get('IdEncabezado').'_'.str_replace(' / ','',$request->session()->get('Periodo')).'.pdf';


            $pdfr = PDF::loadView('pdf_resumen', compact('DS_Resumen','TextResumen','DS_Extras','Cliente','Periodo','IVA','sumTotalConIva','sumSubTotal','TotIva'));

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
