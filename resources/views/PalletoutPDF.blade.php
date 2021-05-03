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
            <h5>PALLET-OUT</h5>
        </div>
    </div>
    <div class="row">
        <div class="col">Bultos: {{$SumBultos}}</div>
    </div>

    <br clear="all">
    <table  cellspacing="0" style="border-bottom:#000000 solid 1px;font-size:14px;">
      <tr>
        <td style="background-color: #cccccc;width:20mm;">Fecha Alta</td>
        <td style="background-color: #cccccc;width:20mm;">Cliente</td>
        <td style="background-color: #cccccc;width:20mm;">Remito #</td>
        <td style="background-color: #cccccc;width:20mm;">Documento</td>
        <td style="background-color: #cccccc;width:25mm;text-align:right">Bulto Remito</td>
      </tr>
      @foreach ($DS_PDF_Palletout as $DatosE)
            <tr style="border-bottom: #000000 solid 1px;">
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->fechaesp}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->cliente_nombre}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->remito}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->documento}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->bultoremito}}</td>
            </tr>
      @endforeach
    </table>
  </body>
</html>
