<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pallet-Out</title>
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
            <h5>PICKING-DEV</h5>
        </div>
    </div>
    <div class="row">
        <div class="col">Tot.Unid.Pickeadas: {{$SumTotUnidPickeadas}}</div>
        <div class="col">Tot.Cajas c/Dec: {{$SumTotCajasDecimales}}</div>
        <div class="col">Tot.Cajas: {{$SumTotCajas}}</div>
        <div class="col">Tot.Unidades: {{$SumUnidades}}</div>
        <div class="col">Tot.Pickeadas: {{$SumTotPickeadas}}</div>
    </div>
    <br clear="all">

    <table  cellspacing="0" style="border-bottom:#000000 solid 1px;font-size:14px;">
      <tr>
        <td style="background-color: #cccccc;width:20mm;">Artículo</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Pedida</td>
        <td style="background-color: #cccccc;width:30mm;text-align:right;">Delivery</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Master</td>
        <td style="background-color: #cccccc;width:40mm;text-align:right;">Unidades Pickeadas</td>
        <td style="background-color: #cccccc;width:30mm;text-align:right;">Caja/Dec</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Cajas</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Unidades</td>
        <td style="background-color: #cccccc;width:25mm;text-align:right">Pickeadas</td>
      </tr>
      @foreach ($DS_PDF_PICKINGDEV as $DatosE)
            <tr style="border-bottom: #000000 solid 1px;">
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->articulo_codigo}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->cant_documento_unidad}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->documento}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->master}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->cant_preparada_unidad}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->ColCajaDecimales}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->ColCajas}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->ColUnidades}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->ColPickeadas}}</td>
            </tr>
      @endforeach
    </table>
  </body>
</html>
