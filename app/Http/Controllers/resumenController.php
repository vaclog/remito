<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade as PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class resumenController extends Controller
{
    public function Formulario()
    {
        $clientes = DB::table('clients')->select('vkm_cliente_id as id','razon_social')->orderByRaw('razon_social asc')->get();
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

        #Verifico estado de la operacion aca sabe que las operaciones estan en 0
        $Es=DB::select("select fx_verifica_estado_operaciones(?,?,?,?) as Salida",[$request->cbo_clientes,$F_Anio,$F_Mes,5]);
        foreach($Es as $valor)
        {
            $Estado=$valor->Salida;
        }

        #Nombre cliente
        $DS_RazonSocial = DB::table('clients')->select('razon_social')->whereRaw("vkm_cliente_id=$request->cbo_clientes")->pluck('razon_social');
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
        return view('resumenGrid',compact('DSItemsJson','_ErrorPI','_ErrorPO','_ErrorPC','_ErrorAL','_Error','_DatosFecha','_Editable','Estado','F_Mes','F_Anio'));

    }

    public function SP_Grabar(Request $request)
    {
        $_Error=0;

        try{
            #Llama al SP para actualizar los items
            $DS_sp_items = DB::select("call sp_actualiza_items_resumen(?,?,?,?)",[$request->idrec,$request->Desc,$request->Imp,$request->ColIdx]);
            $_Error=0;
        }catch(\Exception $e)
        {
            $_Error=1;
        }

        $_Salida=array('Error'=>$_Error);
        echo json_encode($_Salida);
    }

    public function ExcelExport(Request $request)
    {
        $_ErrorResumen=0;
        $_ErrMsg='';
        $sumTotalConIva=0;
        $sumSubTotal=0;
        $TotIva=0;

        try{
/*
            if($request->Accion==1)
            {
                $IdEnc=$request->session()->get('IdEncabezado');
                $DS_sp_cierra_resumen = DB::select("call sp_cierra_resumen(?,?)",[$IdEnc,0]);
                $_ErrorResumen=0;
            }

  */
            $IdEnc=$request->session()->get('IdEncabezado');
            #$DS_sp_cierra_resumen = DB::select("call sp_cierra_resumen(?,?)",[$IdEnc,0]);


            if($request->Accion==1)
            {
                # aca le mando 0 para que se pueda reprocesar varias veces
                $DS_sp_cierra_resumen = DB::statement("call sp_cierra_resumen(?,?)",[$IdEnc,0]);
                $_ErrorResumen=0;
            }else{
                # aca le mando 1 para que cierre todo
                $DS_sp_cierra_resumen = DB::statement("call sp_cierra_resumen(?,?)",[$IdEnc,1]);
                $_ErrorResumen=0;
            }

            $NombreXLS=$request->session()->get('RazonSocial').'_'.str_replace(' / ','',$request->session()->get('Periodo'));

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->setTitle("Resumen");

            $this->sumTotalConIva=0;
            $this->sumSubTotal=0;
            $this->TotIva=0;
            $this->Cliente=$request->session()->get('RazonSocial');
            $this->Periodo=$request->session()->get('Periodo');

            /******************************************************************************************************** */
            #Datos calculados
            $DS_Resumen = DB::table('vs_resumen_pdf')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado'))->get();
            $_ErrorResumen=0;

            #Sumatorias
            foreach($DS_Resumen as $salida)
            {
                $this->sumTotalConIva+=$salida->PrecioConIva;
                $this->sumSubTotal+=$salida->Precio;
            }

            #Tomo valor IVA
            $DS_IVA = DB::table('vkm_valor_iva')->select('Iva')->pluck('Iva');
            $this->IVA=$DS_IVA[0];

            #Datos de items extras
            $DS_Extras = DB::table('vs_resumen_items_extras')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado')." and Importe>0")->get();
            #Sumatorias
            foreach($DS_Extras as $salida)
            {
                $this->sumTotalConIva+=round($salida->TotIva,2);
                $this->sumSubTotal+=round($salida->Importe,2);
            }

            $this->TotIva=round((($this->sumSubTotal*$this->IVA)/100),2);

            # revisar esta parte
            if($request->Accion==1)
            {
                $this->TextResumen='Prefacturacion Previo';
            }else{
                $this->TextResumen='Prefacturacion Final';
            }

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(20);

            $sheet->setCellValue('A1', $this->TextResumen);
            $sheet->setCellValue('B1', $this->Cliente);

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(14);

            $sheet->setCellValue('A2', 'Período :'.$this->Periodo);
            $sheet->setCellValue('A3', 'Fecha archivo :'.date('d/m/Y'));


            #Titulo de los items
            $sheet->setCellValue('A5', 'Cantidad');
            $sheet->setCellValue('B5', 'Concepto');
            $sheet->setCellValue('C5', 'Precio');

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(12);

            $i=6;

            $DS_Resumen = DB::table('vs_resumen_pdf')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado'))->get();
            foreach($DS_Resumen as $salida)
            {

                $sheet->setCellValue('A'.$i, $salida->Cantidad);
                $sheet->setCellValue('B'.$i, $salida->Concepto);
                $sheet->setCellValue('C'.$i, $salida->Precio);
                $i+=1;
            }

            #Totales
            $i+=2;
            $sheet->setCellValue('B'.$i, 'Sub.Total $');
            $sheet->setCellValue('C'.$i, $this->sumSubTotal);
            $i+=1;
            $sheet->setCellValue('B'.$i, 'IVA  %');
            $sheet->setCellValue('C'.$i, $this->TotIva);
            $i+=1;
            $sheet->setCellValue('B'.$i, 'Total $');
            $sheet->setCellValue('C'.$i, $this->sumTotalConIva);

            #---------------------------------------------- EXTRAS -------------------------------------------------------------------------------------------------
            $spreadsheet->createSheet();
            $sheet1=$spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle("Extras");
/*
            if($request->Accion==1)
            {
                $this->TextResumen='Prefacturacion Previo';
            }else{
                $this->TextResumen='Prefacturacion Final';
            }
*/
            #Titulo de los items
            $sheet1->setCellValue('B1', 'Concepto');
            $sheet1->setCellValue('C1', 'Precio');

            $i=2;
            $DS_Extras = DB::table('vs_resumen_items_extras')->select('*')->whereRaw("Id_Encabezado=".$request->session()->get('IdEncabezado')." and Importe>0")->get();
            foreach($DS_Extras as $salida)
            {

                $sheet1->setCellValue('B'.$i, $salida->Descripcion);
                $sheet1->setCellValue('C'.$i, $salida->Importe);
                $i+=1;
            }


            #---------------------------------------------- PALLET-IN -------------------------------------------------------------------------------------------------
            $spreadsheet->createSheet();
            $sheet2=$spreadsheet->setActiveSheetIndex(2);
            $spreadsheet->getActiveSheet()->setTitle("Pallet-In");
/*
            if($request->Accion==1)
            {
                $this->TextResumen='Prefacturacion Previo';
            }else{
                $this->TextResumen='Prefacturacion Final';
            }
*/

            $sheet2->setCellValue('A1', 'Fecha');
            $sheet2->setCellValue('B1', 'Nro.documento');
            $sheet2->setCellValue('C1', 'Nro.Tarea/IR');
            $sheet2->setCellValue('D1', 'Proveedor');
            $sheet2->setCellValue('E1', 'Referencia');
            $sheet2->setCellValue('F1', 'Tipo Operacion');
            $sheet2->setCellValue('G1', 'Cantidad');
            $sheet2->setCellValue('H1', 'Pallet-In');
            $sheet2->setCellValue('I1', 'Precio Pallet');
            $sheet2->setCellValue('J1', 'Picking Dev');

            $Anio=$request->anio;
            $Mes=$request->mes;
            $Idcliente=$request->session()->get('IDCliente');
            $DS_PDF_Palletin = DB::table('vs_ingresos')->select('*')->whereRaw("year(fecha_alta)=$Anio and month(fecha_alta)=$Mes and cliente_id=$Idcliente")->get();

            $i=2;
            foreach($DS_PDF_Palletin as $salida)
            {
                $sheet2->setCellValue('A'.$i, $salida->fecha_altaes);
                $sheet2->setCellValue('B'.$i, $salida->documento);
                $sheet2->setCellValue('C'.$i, $salida->NroTareaIR);
                $sheet2->setCellValue('D'.$i, $salida->proveedor_nombre);
                $sheet2->setCellValue('E'.$i, $salida->Referencia);
                $sheet2->setCellValue('F'.$i, ($salida->IDTipoOperacion=1?'Nuevo':'Devolucion'));
                $sheet2->setCellValue('G'.$i, $salida->cant_recibida_unidad);
                $sheet2->setCellValue('H'.$i, $salida->CantidadPalletIN);
                $sheet2->setCellValue('I'.$i, $salida->PrecioPalletIN);
                $sheet2->setCellValue('J'.$i, $salida->PickingDev);
                $i+=1;
            }

            #---------------------------------------------- PALLET-OUT -------------------------------------------------------------------------------------------------
            $spreadsheet->createSheet();
            $sheet3=$spreadsheet->setActiveSheetIndex(3);
            $spreadsheet->getActiveSheet()->setTitle("Pallet-Out");
/*
            if($request->Accion==1)
            {
                $this->TextResumen='Prefacturacion Previo';
            }else{
                $this->TextResumen='Prefacturacion Final';
            }
*/
            $sheet3->setCellValue('A1', 'Fecha alta');
            $sheet3->setCellValue('B1', 'Cliente');
            $sheet3->setCellValue('C1', 'Remito #');
            $sheet3->setCellValue('D1', 'Documento');
            $sheet3->setCellValue('E1', 'Bulto Remito');

            $Anio=$request->anio;
            $Mes=$request->mes;
            $Idcliente=$request->session()->get('IDCliente');
            $DS_PDF_Palletout = DB::table('vs_salidas')->select('*')->whereRaw("year(fecha_alta)=$Anio and month(fecha_alta)=$Mes and cliente_id=$Idcliente")->get();

            $i=2;
            foreach($DS_PDF_Palletout as $salida)
            {
                $sheet3->setCellValue('A'.$i, $salida->fechaesp);
                $sheet3->setCellValue('B'.$i, $salida->cliente_nombre);
                $sheet3->setCellValue('C'.$i, $salida->remito);
                $sheet3->setCellValue('D'.$i, $salida->documento);
                $sheet3->setCellValue('E'.$i, $salida->bultoremito);
                $i+=1;
            }

            #---------------------------------------------- PICKING -------------------------------------------------------------------------------------------------
            $spreadsheet->createSheet();
            $sheet4=$spreadsheet->setActiveSheetIndex(4);
            $spreadsheet->getActiveSheet()->setTitle('Picking');
/*
            if($request->Accion==1)
            {
                $this->TextResumen='Prefacturacion Previo';
            }else{
                $this->TextResumen='Prefacturacion Final';
            }

*/
            $sheet4->setCellValue('A1', 'Articulo');
            $sheet4->setCellValue('B1', 'Pedida');
            $sheet4->setCellValue('C1', 'Delivery');
            $sheet4->setCellValue('D1', 'Master');
            $sheet4->setCellValue('E1', 'Unidades Pickeadas');
            $sheet4->setCellValue('F1', 'Caja/Dec');
            $sheet4->setCellValue('G1', 'Cajas');
            $sheet4->setCellValue('H1', 'Unidades');
            $sheet4->setCellValue('I1', 'Pickeadas');

            $Anio=$request->anio;
            $Mes=$request->mes;
            $Idcliente=$request->session()->get('IDCliente');
            $DS_PDF_PICKINGDEV = DB::select("call sp_ls_picking(?,?,?)",[$request->session()->get('IDCliente'),$Anio,$Mes]);

            $i=2;
            foreach($DS_PDF_PICKINGDEV as $salida)
            {
                $sheet4->setCellValue('A'.$i, $salida->articulo_codigo);
                $sheet4->setCellValue('B'.$i, $salida->cant_documento_unidad);
                $sheet4->setCellValue('C'.$i, $salida->documento);
                $sheet4->setCellValue('D'.$i, $salida->master);
                $sheet4->setCellValue('E'.$i, $salida->cant_preparada_unidad);
                $sheet4->setCellValue('F'.$i, $salida->ColCajaDecimales);
                $sheet4->setCellValue('G'.$i, $salida->ColCajas);
                $sheet4->setCellValue('H'.$i, $salida->ColUnidades);
                $sheet4->setCellValue('I'.$i, $salida->ColPickeadas);
                $i+=1;
            }


            #---------------------------------------------- ALMACENAJE -------------------------------------------------------------------------------------------------
            $spreadsheet->createSheet();
            $sheet5=$spreadsheet->setActiveSheetIndex(5);
            $spreadsheet->getActiveSheet()->setTitle('Almacenaje');
/*
            if($request->Accion==1)
            {
                $this->TextResumen='Prefacturacion Previo';
            }else{
                $this->TextResumen='Prefacturacion Final';
            }
*/
            $sheet5->setCellValue('A1', 'Fecha');
            $sheet5->setCellValue('B1', 'Pallet');
            $sheet5->setCellValue('C1', 'Piso Despacho');
            $sheet5->setCellValue('D1', 'Piso S.Ubic');
            $sheet5->setCellValue('E1', 'Piso S.Ing');
            $sheet5->setCellValue('F1', 'MKT');
            $sheet5->setCellValue('G1', 'Atelier');
            $sheet5->setCellValue('H1', 'Estanteria');
            $sheet5->setCellValue('I1', 'Tot.Almacenaje');

            $Anio=$request->anio;
            $Mes=$request->mes;
            $Idcliente=$request->session()->get('IDCliente');
            $DS_PDF_ALMACENAJE = DB::table('vs_almacenaje')->select('*')->whereRaw("mes=$Mes and anio=$Anio and cliente_id=$Idcliente")->get();

            $i=2;
            foreach($DS_PDF_ALMACENAJE as $salida)
            {
                $sheet5->setCellValue('A'.$i, $salida->fechaes);
                $sheet5->setCellValue('B'.$i, $salida->Pallet);
                $sheet5->setCellValue('C'.$i, $salida->PisoDespacho);
                $sheet5->setCellValue('D'.$i, $salida->PisoSUbicacion);
                $sheet5->setCellValue('E'.$i, $salida->PisoSIngresar);
                $sheet5->setCellValue('F'.$i, $salida->MKT);
                $sheet5->setCellValue('G'.$i, $salida->Atelier);
                $sheet5->setCellValue('H'.$i, $salida->Estanteria);
                $sheet5->setCellValue('I'.$i, $salida->TotalAlmacenaje);
                $i+=1;
            }


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$NombreXLS.'.xlsx'.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        }catch(\Exception $e){
            echo $e->getMessage();
            $_ErrMsg = $e->getMessage();
            $_ErrorResumen=1;
        }
    }

}
