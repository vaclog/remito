<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pallet-In</title>
  </head>
  <body>
    <div style="float:left;"><img src="images/VACLOG.jpg" width="200px;" style="margin-bottom: 50px;"></div>
    <div style="float:right;">Fecha creado {{date('d/m/Y')}}</div>
    <br><br><br><br clear="all">
    <div style="float:left;">Cliente {{$Cliente}}</div>
    <div style="float:right;">Período {{$Periodo}}</div>
    <br><br>
    <div class="row">
        <div class="col-12">
            <h5>PALLET-IN</h5>
        </div>
    </div>
    <div class="row">
        <div class="col">Cant:          {{$SumCant}}</div>
        <div class="col">Pallet-In:     {{$SumPalletin}}</div>
        <div class="col">Precio Pallet: {{$SumPrecio}}</div>
        <div class="col">Picking Dev:   {{$SumPicking}}</div>
    </div>
    <br clear="all">

    <table  cellspacing="0" style="border-bottom:#000000 solid 1px;font-size:14px;">
      <tr>
        <td style="background-color: #cccccc;width:20mm;">Fecha</td>
        <td style="background-color: #cccccc;width:20mm;">Nro.Documento</td>
        <td style="background-color: #cccccc;width:20mm;">Nro.Tarea/IR</td>
        <td style="background-color: #cccccc;width:20mm;">Proveedor</td>
        <td style="background-color: #cccccc;width:25mm;">Referencia</td>
        <td style="background-color: #cccccc;width:20mm;">Tipo Operación</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Cantidad</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Pallet In</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Precio Pallet</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Picking Dev</td>
      </tr>
      @foreach ($DS_PDF_Palletin as $DatosE)
            <tr style="border-bottom: #000000 solid 1px;">
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->fecha_altaes}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->documento}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->NroTareaIR}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->proveedor_nombre}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->Referencia}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">
                    @if($DatosE->IDTipoOperacion==1)
                        Nuevo
                    @else
                        Devolución
                    @endif
                </td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->cant_recibida_unidad}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->CantidadPalletIN}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->PrecioPalletIN}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->PickingDev}}</td>
            </tr>
      @endforeach
    </table>
  </body>
</html>
