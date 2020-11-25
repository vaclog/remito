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
            <h5>ALMACENAJE</h5>
        </div>
    </div>
    <div class="row">
        <div class="col">Stock Máximo: {{ $StockMaximo }}</div>
        <div class="col">Stock Base: {{$PrecioCostos}}</div>
        <div class="col">Excedente: {{$Excedente}}</div>
    </div>
    <br clear="all">

    <table  cellspacing="0" style="border-bottom:#000000 solid 1px;font-size:14px;">
      <tr>
        <td style="background-color: #cccccc;width:10mm;">Fecha</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Pallet</td>
        <td style="background-color: #cccccc;width:30mm;text-align:right;">Piso Despacho</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Piso.S.Ubic</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Piso.S.Ing</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">MKT</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Atelier</td>
        <td style="background-color: #cccccc;width:20mm;text-align:right;">Estanteria</td>
        <td style="background-color: #cccccc;width:30mm;text-align:right">Tot.Almacenaje</td>
      </tr>
      @foreach ($DS_PDF_ALMACENAJE as $DatosE)
            <tr style="border-bottom: #000000 solid 1px;">
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->fechaes}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->Pallet}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->PisoDespacho}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->PisoSUbicacion}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->PisoSIngresar}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->MKT}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->Atelier}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->Estanteria}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->TotalAlmacenaje}}</td>
            </tr>
      @endforeach
    </table>
  </body>
</html>
