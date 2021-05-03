@extends('plantillabase')
@section('areatrabajo')

    @if($Estado!=0)
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-danger" role="alert">OPERACION CERRADA</div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <h3>Almacenaje</h3>
            <h3>Cliente: {{ Session('RazonSocial') }}</h3>
            <h5>Mes/Año {!! $FechaVista !!}</h5>
            <h5><a href="javascript:PDF();"><i class='far fa-file-pdf' style='font-size:24px;color:red'></i></a></h5>
        </div>

        <div class="col-6 text-right">
            <h5>Stock Máximo: <input id="StockMaximo" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $StockMaximo }}"/></h5>
            <h5>Stock Base: <input id="PrecioCostos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $PrecioCostos }}"/></h5>
            <h5>Excedente: <input id="Excedente" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $Excedente }}"/></h5>
        </div>
    </div>

    <div id="grid" style="width: 100%; height: 500px;"></div>

    <script type="text/javascript" src="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />

    <script type="text/javascript">
        var controlrec=0;
        var sel_record;
        var _Operacion={{$Estado}};
        //var _NoEditar=$('#errormsg').val();

        $(function () {
            $('#grid').w2grid({
                name: 'grid',
                show: {
                    toolbar: true,
                    footer: true,
                    toolbarSave: false
                },
                columns: [
                    { field: 'recid', caption: 'ID', size: '50px', sortable: true, resizable: true,hidden:true },
                    { field: 'cliente_id', caption: 'ID.Cliente',hidden:true, size: '50px', sortable: true, resizable: true },
                    { field: 'fechaes', caption: 'Fecha', size: '80px', sortable: true, resizable: true,style: 'text-align: left'},
                    { field: 'Pallet',editable:{type: 'number:2'}, caption: '<b>Pallet</b>', size: '80px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'PisoDespacho',editable:{type: 'number:2'}, caption: '<b>Piso Despacho</b>', size: '100px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'PisoSUbicacion',editable:{type: 'number:2'}, caption: '<b>Piso.S.Ubic</b>', size: '100px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'PisoSIngresar',editable:{type: 'number:2'}, caption: '<b>Piso.S.Ing</b>', size: '100px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'MKT',editable:{type: 'number:2'}, caption: '<b>MKT</b>', size: '80px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'Atelier',editable:{type: 'number:2'}, caption: '<b>Atelier</b>', size: '80px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'Estanteria',editable:{type: 'number:2'}, caption: '<b>Estanteria</b>', size: '100px', sortable: true, resizable: true,style: 'text-align: right'},
                    { field: 'TotalAlmacenaje', caption: 'Tot.Almacenaje', size: '100px', sortable: true, resizable: true,style: 'text-align: right'}
                ],
                records:<?php echo($DSAlmacenajeJson);?>,
                onChange: function (event) {
                    event.onComplete = function() {
                        var grid = this;
                        //var idregistro=0;
                        var sel_rec_ids = grid.getSelection();
                        if (sel_rec_ids.length)
                        {
                            sel_record = grid.get(sel_rec_ids[0]);
                            controlrec=sel_rec_ids[0];
                        }

                        switch(event.column)
                        {
                            case 3:
                                let res=BuscarValorCelda();
                                if($.isNumeric(res.cnt))
                                {
                                    Calcular(res.idregistro,3,res.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                            case 4:
                                let res4=BuscarValorCelda();
                                if($.isNumeric(res4.cnt))
                                {
                                    Calcular(res4.idregistro,4,res4.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                            case 5:
                                let res5=BuscarValorCelda();
                                if($.isNumeric(res5.cnt))
                                {
                                    Calcular(res5.idregistro,5,res5.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                            case 6:
                                let res6=BuscarValorCelda();
                                if($.isNumeric(res6.cnt))
                                {
                                    Calcular(res6.idregistro,6,res6.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                            case 7:
                                let res7=BuscarValorCelda();
                                if($.isNumeric(res7.cnt))
                                {
                                    Calcular(res7.idregistro,7,res7.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                            case 8:
                                let res8=BuscarValorCelda();
                                if($.isNumeric(res8.cnt))
                                {
                                    Calcular(res8.idregistro,8,res8.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                            case 9:
                                let res9=BuscarValorCelda();
                                if($.isNumeric(res9.cnt))
                                {
                                    Calcular(res9.idregistro,9,res9.cnt);
                                    w2ui['grid'].save();
                                }else{
                                    alert("¡Solo se permiten valores numéricos!");
                                }
                                break;
                        } //switch
                    } //oncomplete
                } //onchange
            });
        });

        function BuscarValorCelda()
        {
            datos=w2ui['grid'].getChanges();
            v=0;
            for (var i = 0; i < datos.length; i++){
                var obj = datos[i];
                for (var key in obj){
                    var value = obj[key];
                    if(v==0){
                        idregistro=value; //Es el recid
                        v=1;
                    }else{
                        cnt=value; //Es el valor contretamente
                        v=0;
                    }
                }
            }
            return {idregistro,cnt};
        }

        function Calcular(Registro,Columna,Valor)
        {
            if(_Operacion==1)
            {
                alert('¡La operación esta cerrada, no se puede editar!');
                return;
            }

            $.ajax({
              type:"POST",
              url:"{{ route('ajax.almacenaje') }}",
              contentType: "application/x-www-form-urlencoded; charset=utf-8;",
              data: {idrec:Registro,col:Columna,valor:Valor,_token:"{{ csrf_token() }}" },
              cache: false,
              dataType:'json',
              timeout: 0,
              error: function( request, error ){alert('Error al ejecutar la transacción');},
              //beforeSend: function(){alert(controlupdate);},
              success: function(resultado)
              {
                    if(resultado.Error!=0)
                    {
                        alert('Error al ejecutar la transacción');
                    }else{
                        w2ui['grid'].set(controlrec, {TotalAlmacenaje:resultado.Total});
                        $('#StockMaximo').val(resultado.StockMaximo);
                        $('#PrecioCostos').val(resultado.StockBase);
                        $('#Excedente').val(resultado.Excedente);
                    }
                }
            });
        }

        function PDF()
        {
            $.ajax({
                type:"POST",
                url:"{{ route('PDFAlmacenaje') }}",
                contentType: "application/x-www-form-urlencoded; charset=utf-8;",
                data: {Cliente:{{$ID_Cliente}},Anio:"{{$Anio}}",Mes:"{{$Mes}}",_token:"{{ csrf_token() }}" },
                cache: false,
                dataType:'json',
                timeout: 0,
                error: function( request, error ){alert('Error al ejecutar la transacción');},
                //beforeSend: function(){alert(controlupdate);},
                success: function(resultado)
                {
                    if(resultado.Error!=0)
                    {
                        alert('Error al ejecutar la transacción - ' + resultado.Msg);
                    }else{
                        window.open(resultado.Path, '_blank');;
                    }
                }
            });
        }

    </script>
@endsection
