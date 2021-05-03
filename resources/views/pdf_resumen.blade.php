<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Prefacturacion</title>
  </head>
  <body>
    <div style="float:left;"><img src="images/VACLOG.jpg" width="200px;" style="margin-bottom: 50px;"></div>
    <div style="float:right;">Fecha creado {{date('d/m/Y')}}</div>
    <br><br><br><br clear="all">

    <h3>{{$TextResumen}}</h3>
    <table  cellspacing="0" style="width:100%">
        <tr>
            <td style="width:70%">Cliente {{$Cliente}}</td>
            <td style="width:50%;text-align:right;">Período {{$Periodo}}</td>
        </tr>
    </table>
    <hr>

    <table  cellspacing="0" style="border-bottom:#000000 solid 1px;width:700px;">
      <tr>
        <td style="background-color: #cccccc">Cantidad</td>
        <td style="background-color: #cccccc">Concepto</td>
        <td style="background-color: #cccccc;text-align:right;">Precio</td>
      </tr>
      @foreach ($DS_Resumen as $Datos)
            <tr style="border-bottom: #000000 solid 1px;">
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$Datos->Cantidad}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$Datos->Concepto}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">$ {{$Datos->Precio}}</td>
            </tr>
      @endforeach
      @foreach ($DS_Extras as $DatosE)
            <tr style="border-bottom: #000000 solid 1px;">
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">0</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;">{{$DatosE->Descripcion}}</td>
                <td style="border-bottom-color: #cccccc;border-bottom: solid;border-width: 1px;text-align:right;">{{$DatosE->Importe}}</td>
            </tr>
      @endforeach
    </table>
    <br clear="all">
    <table  cellspacing="0" style="width:700px;">
        <tr>
            <td style="text-align:right;">Sub Total:</td>
            <td style="text-align:right;width:140px;">$ {{$sumSubTotal}}</td>
        </tr>
        <tr>
        <td style="text-align:right;">IVA: {{$IVA}} %</td>
            <td style="text-align:right;">$ {{$TotIva}}</td>
        </tr>
        <tr>
            <td style="text-align:right;">Total:</td>
            <td style="text-align:right;">$ {{$sumTotalConIva}}</td>
        </tr>
    </table>
  </body>
</html>
