<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
            @page {
                margin: 0cm 0cm;
            }
            /** Define now the real margins of every page in the PDF **/
            
            .pagenum:before {
                content: counter(page);
            }

            /*.etiqueta_copia:before {
                content: 'Original';
            }*/

            

           
            
            body {
                margin-top: 7cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
                margin-bottom: 3.5cm;
            }
            
            table {
                width: 100%;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }
            /** Define the header rules **/
            
            header {
                position: fixed;
                top: 0.5cm;
                left: 0.5cm;
                right: 0.5cm;
                height: 5.5cm;
                /** Extra personal styles **/
                /*background-color: #03a9f4;
                    color: white;
                    text-align: center;*/
                /*line-height: 1.5cm;*/
            }
            /** Define the footer rules **/
            
            footer {
                position: fixed;
                bottom: 0cm;
                left: 0.5cm;
                right: 0.5cm;
                height: 3.5cm;
                /** Extra personal styles **/
                
                
                font-size: 12px;
                /*color: white;*/
                /*text-align: center;
                line-height: 1.5cm;*/
            }

            .cai{
                background-color: lightgrey;
                border: 1.2px solid black;
                border-collapse: collapse;
                font-weight: normal;
            }
            .cai>thead>tr>th{
                font-size: 12px;

                font-weight: normal;
            }
            
            .brand {
                height: 50px;
                border: 1.2px solid black;
                border-collapse: collapse;
            }
            
            .letra {
                border: 1.2px solid black;
                border-collapse: collapse;
                font-size: 40px;
                vertical-align: middle;
                text-align: center;
            }

            .original-duplicado {
                text-align: right;
                font-size: 12px;
                
            }
            
            .company {
                font-size: 25px;
                padding-top: 25px;
            }
            
            .brand-st-left {
                text-align: right;
                padding-right: 5px;
            }
            
            .nro_remito {
                font-weight: normal;
                text-align: left
            }
            
            .barras {
                font-weight: normal;
                text-align: left;
                font-size: 11px;
                vertical-align: bottom;
            }
            
            .brand-st-rigth {
                padding-left: 5px;
            }
            
            p {
                padding: 0px, 0px, 0px, 0px;
                font-size: 11px
            }
            
            .pagina {
                font-size: 12px;
                text-align: right;
                padding-right: 5px;
                padding-bottom: 5px;
                vertical-align: bottom;
            }
            
            
            
            .entrega>thead>tr>th {
                padding-left: 5px;
            }
            
            .entrega>thead>tr {
                text-align: left;
                font-size: 14px;
            }
            
            .entrega .atributo {
                font-weight: normal;
                font-size: 12px;
            }
            
           
            
            .entrega .divisor-vertical {
                border-right: 1.2px solid black;
                border-collapse: collapse;
            }
            
            .observaciones {
                height: 10mm;
                border-bottom: 1.2px solid black;
            }
            
            .item-head,
            .item-head>td {
                background-color: lightgrey;
                font-size: 12px;
                font-weight: bold;
            }

           
            
            .articulos>tbody>tr>td {
                font-size: 12px;
            }

            .cantidad{
                font-size: 1.9rem;
                text-align: right;
            }
          

             .articulos>.footer{
                background-color: lightgrey;
                border-top: 1.2px solid gray;
                color: black;
                font-size: 0.9rem;
                padding-top: 15px;
                font-weight: bolder;
            } 
            
            .entrega>thead>tr {
                text-align: left;
                font-size: 12px;
            }
            
            .entrega .atributo {
                font-weight: normal;
                font-size: 12px;
            }
            
            .entrega {
                border-bottom: 1.2px solid black;
                border-collapse: collapse;
                
                
                line-height: 1.2rem;
                height: 2cm;
            }
            
            .entrega .divisor-vertical {
                border-right: 1.2px solid black;
                border-collapse: collapse;
            }

            .firma {
                padding-top: 2cm;
            }

            .observaciones-texto{
                font-size: 13px;
                font-weight: bold;
                padding: 5px;
                text-align: left;
                vertical-align: top
            }
        </style>
</head>

<body>
    <header>
        <table class="brand">
            <thead>
                <tr>
                    <th style="width: 47%" class="brand-st-left">
                        <strong class="company">{{$data->client->razon_social}}</strong>
                    </th>
                    <th class="letra" rowspan="2" style="width: 6%">
                        R
                    </th>
                    <th style="width: 18%;text-align: left" class="brand-st-rigth">
                        <strong>Nro. Remito:</strong> <br>
                        <strong>Fecha:</strong>
                    </th>
                    <th style="width: 17%" class="nro_remito">
                            {{ str_pad( $data->sucursal, 4, "0", STR_PAD_LEFT).'-'.str_pad( $data->numero_remito, 8, "0", STR_PAD_LEFT)}}
                            <br> 
                            {{ (strtotime($data->fecha_remito)> 0)?date_format(date_create($data->fecha_remito), 'd/m/Y'):'-'    }}
                    </th>
                    <th style="width: 12%" class="barras">
                        <br> <p class="etiqueta_copia"></p> 
                    </th>
                </tr>
                <tr>
                    <td colspan="1" class="brand-st-left">
                        <p> San Blas 1760 | 1416 | Capital | Argentina<br> Tel 5274-8500| eMail: info@orien.com.ar<br> I.V.A. Responsable Inscripto | Imp. Internos: No Responsable
                        </p>
                    </td>
                    <td colspan="2" class="brand-st-rigth">
                        <p>
                            C.U.I.T.: 30686262312<br> 
                            ING. BRUTOS: 0989034-03<br> 
                            INICIO DE ACTIVIDAD: 01/11/1996
                        </p>
                    </td>
                    <td class="pagina"> <span lass="pagenum"></span></td>

                </tr>

            </thead>

        </table>

        <table class="entrega">
            <thead>
                <tr>
                    <th style="width: 10%"></th>
                    <th style="width: 40%" class="atributo divisor-vertical"></th>
                    <th style="width: 10%"></th>
                    <th style="width: 40%" class="atributo">
                    </th>

                </tr>
                <tr>
                    <th style="width: 10%">Cliente</th>
                    <th class="atributo divisor-vertical" style="width: 40%">
                        {{ $data->customer->nombre}}
                    </th>
                    <th style="width:10%">TRANSPORTE:</th>
                    <th style="width: 40%" class="atributo">{{ substr($data->transporte, 0, 40) }}</th>


                </tr>
                <tr>
                    <th style="width: 10%">CUIT</th>
                    <th style="width: 40%" class="atributo divisor-vertical">{{$data->customer->cuit}}</th>
                    <th style="width: 10%">CHOFER:</th>
                    <th style="width: 40%" class="atributo">
                        {{ $data->conductor}}
                    </th>

                </tr>
                <tr>
                    <th style="width: 10%">Domicilio</th>
                    <th style="width: 40%" class="atributo divisor-vertical">
                        {{
                            substr(($data->calle.' | '.$data->localidad.' | '.$data->provincia), 0, 50)
                        }}
                    </th>
                    <th style="width: 10%">DOMINIO</th>
                    <th style="width: 40%" class="atributo">{{ $data->patente }}</th>
                </tr>
            </thead>

        </table>

        <table class="observaciones">
            <tr>
                <td valign="top" style="width:10%">Observaciones:</td>
                <td class="observaciones-texto" style="width:90%;">{{ $data->observaciones }}</td>

            </tr>
            <tr>
                <td valign="top">Nro Pedido: </td>
                <td class="">{{$data->referencia}}</td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="cai">
            <thead>
                <tr>
                    <th style="width: 33%">
                    </th>
                    <th style="width: 33%">
                        CAI: {{ $data->cai }}
                    </th>
                    <th style="width: 33%">
                        VENCIMIENTO: {{(strtotime($data->cai_vencimiento)> 0)?date_format(date_create($data->cai_vencimiento), 'd/m/Y'):''}}
                    </th>
                </tr>
            </thead>
    
        </table>
        <table class="firma">
            <thead>
                <tr>
                    <td>Bultos:</td>
                    <td>_______</td>
                    <td>Peso Aprox:</td>
                    <td>_____________</td>
                    <td>Recibí conforme (firma y aclaracion)</td>
                    <td align="right">_________________________________</td>
                </tr>
            </thead>
        </table>
    </footer>

    <main>
       
        <table class="articulos" id="tabla1" name="tabla1">

            <thead class="item-head">
                <tr>
                    <td style="width: 10%">
                        SKU
                    </td>
                    <td style="width: 35%">
                        Articulo
                    </td>
                    <td style="width: 15%">
                        Ean13
                    </td>
                    <td style="width: 10%">
                        Lote
                    </td>
                    <td style="width: 10%">
                        Vencimiento
                    </td>
                    <td style="width: 10%">
                        UM
                    </td>
                    <td style="width: 10%">
                        Cantidad
                    </td>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($data['articulos'] as $item)
                <tr>
                    <td>{{$item['codigo']}}</td>
                    <td>{{$item['descripcion']}}</td>
                    <td>{{$item['ean13']}}</td>
                    <td>{{$item['lote']}}</td>
                    <td>{{
                            (strtotime($item['fecha_vencimiento'])> 0)?date_format(date_create($item['fecha_vencimiento']), 'd/m/Y'):'-'    
                    }}</td>
                    <td>{{ $item['unidad_medida']}}</td>
                    <td class="cantidad">{{$item['cantidad']}}</td>
                </tr>

                @endforeach

            </tbody>
            <tfoot class="footer">
                <tr>
                    <td colspan="2" tipo_etiqueta="tabla1" class="etiqueta_copia">TOTAL de LINEAS</td>
                    <td colspan="2">{{ count($data['articulos'])  }}</td>
                    <td colspan="3" class="cantidad">{{$data->articulos->sum('cantidad')}}</td>
                </tr>
                <tr>
                    <td colspan="7" class="original-duplicado"> <span class="original-duplicado"> ORIGINAL</span></td>
                </tr>
            </tfoot>
        </table>
        <p style="page-break-after: always;">     
        </p>
        

        <table class="articulos" id="tabla2" name="tabla2" style="counter-reset: page;">

            <thead class="item-head">
                <tr>
                    <td style="width: 10%">
                        SKU
                    </td>
                    <td style="width: 35%">
                        Articulo
                    </td>
                    <td style="width: 15%">
                        Ean13
                    </td>
                    <td style="width: 10%">
                        Lote
                    </td>
                    <td style="width: 10%">
                        Vencimiento
                    </td>
                    <td style="width: 10%">
                        UM
                    </td>
                    <td style="width: 10%">
                        Cantidad
                    </td>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($data['articulos'] as $item)
                <tr>
                    <td>{{$item['codigo']}}</td>
                    <td>{{$item['descripcion']}}</td>
                    <td>{{$item['ean13']}}</td>
                    <td>{{$item['lote']}}</td>
                    <td>{{
                            (strtotime($item['fecha_vencimiento'])> 0)?date_format(date_create($item['fecha_vencimiento']), 'd/m/Y'):'-'    
                    }}</td>
                    <td>{{ $item['unidad_medida']}}</td>
                    <td class="cantidad">{{$item['cantidad']}}</td>
                </tr>

                @endforeach

            </tbody>
            <tfoot class="footer">
                <tr>
                    <td colspan="2" class="etiqueta_copia">TOTAL de LINEAS</td>
                    <td colspan="2">{{ count($data['articulos'])  }}</td>
                    <td colspan="3" class="cantidad">{{$data->articulos->sum('cantidad')}}</td>
                </tr>
                <tr>
                    <td colspan="7" class="original-duplicado"> <span class="original-duplicado"> DUPLICADO</span></td>
                </tr>
            </tfoot>
        </table>

        <p style="page-break-after: always;">     
        </p>
        <table class="articulos" id="tabla3" name="tabla3" style="counter-reset: page;">

            <thead class="item-head">
                <tr>
                    <td style="width: 10%">
                        SKU
                    </td>
                    <td style="width: 35%">
                        Articulo
                    </td>
                    <td style="width: 15%">
                        Ean13
                    </td>
                    <td style="width: 10%">
                        Lote
                    </td>
                    <td style="width: 10%">
                        Vencimiento
                    </td>
                    <td style="width: 10%">
                        UM
                    </td>
                    <td style="width: 10%">
                        Cantidad
                    </td>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($data['articulos'] as $item)
                <tr>
                    <td>{{$item['codigo']}}</td>
                    <td>{{$item['descripcion']}}</td>
                    <td>{{$item['ean13']}}</td>
                    <td>{{$item['lote']}}</td>
                    <td>{{
                            (strtotime($item['fecha_vencimiento'])> 0)?date_format(date_create($item['fecha_vencimiento']), 'd/m/Y'):'-'    
                    }}</td>
                    <td>{{ $item['unidad_medida']}}</td>
                    <td class="cantidad">{{$item['cantidad']}}</td>
                </tr>

                @endforeach

            </tbody>
            <tfoot class="footer">
                <tr>
                    <td colspan="2" class="etiqueta_copia">TOTAL de LINEAS</td>
                    <td colspan="2">{{ count($data['articulos'])  }}</td>
                    <td colspan="3" class="cantidad">{{$data->articulos->sum('cantidad')}}</td>
                </tr>
                <tr>
                    <td colspan="7" class="original-duplicado"> <span class="original-duplicado"> TRIPICADO </span></td>
                </tr>
            </tfoot>
    </main>


    
</body>

</html>
