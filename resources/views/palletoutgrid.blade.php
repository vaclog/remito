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
            <h3>Pallet-Out</h3>
            <h3>Cliente: {{ Session('RazonSocial') }}</h3>
            <h5>Fecha: {{ $FechaLabelDesde }}</h5>
            <h5><a href="javascript:PDF();"><i class='far fa-file-pdf' style='font-size:24px;color:red'></i></a></h5>
        </div>
        <div class="col-6 text-right">
            <h5>Bultos: <input id="sumbultos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumBultos }}"/></h5>
        </div>
    </div>

    <div id="grid" style="width: 100%; height: 500px;"></div>

    <script type="text/javascript" src="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />

    <script type="text/javascript">
        var controlrec=0;
        var sel_record;
        var _Operacion={{$Estado}};
        var _NoEditar=$('#errormsg').val();

        $(function () {
            $('#grid').w2grid({
                name: 'grid',
                show: {
                    toolbar: true,
                    footer: true,
                    toolbarSave: false
                },
                columns: [
                    { field: 'recid', caption: 'ID', size: '50px', sortable: true, resizable: true,hidden:true},
                    { field: 'fechaesp', caption: 'Fecha alta', size: '80px', sortable: true, resizable: true,style: 'text-align: left'},
                    { field: 'cliente_nombre', caption: 'Cliente', size: '250px', sortable: true, resizable: true,style: 'text-align: left'},
                    { field: 'remito', caption: 'Remito #', size: '80px', sortable: true, resizable: true,style: 'text-align: left'},
                    { field: 'documento', caption: 'Documento', size: '80px', sortable: true, resizable: true, style: 'text-align: left'},
                    { field: 'bultoremito', caption: '<b>Bulto remito</b>',editable:{type: 'int'}, size: '100px', sortable: true, resizable: true,render:'number', style:'text-align:right;'},
                    { field: 'preciopallet', caption: 'Precio Pallet', size: '90px', sortable: true, resizable: true,render:'number',style:'text-align: right',hidden:true}
                ],
                records:<?php echo($DSRemitosJson);?>,
                onChange: function (event) {
                    event.onComplete = function() {
                        var grid = this;
                        var idregistro=0;
                        var cnt=0;
                        var referencia='';
                        var sel_rec_ids = grid.getSelection();
                        if (sel_rec_ids.length)
                        {
                            sel_record = grid.get(sel_rec_ids[0]);
                            controlrec=sel_rec_ids[0];
                        }
                        if (event.column == 5) {
                            //console.log( w2ui['grid'].getChanges() );
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
                            if($.isNumeric(cnt)){
                                if(cnt>-1)
                                {
                                    if(_NoEditar==1)
                                    {
                                        alert('¡No se pueden editar registros sin tarifas activas!');
                                        w2ui['grid'].save();
                                        w2ui['grid'].set(controlrec, {PrecioPalletIN:'0',PickingDev:'0',CantidadPalletIN:'0'});
                                    }else{
                                        CalcularPalletout(cnt,idregistro,1); //1=Indica que es para las cantidades la actialuzacion
                                        w2ui['grid'].save();
                                    }
                                }
                            }
                        } // if 5
                    } // event
                } //onchange
            }); //grid
        }); //function


        function CalcularPalletout(Valor,Reg)
        {
            if(_Operacion==1)
            {
                alert('¡La operación esta cerrada, no se puede editar!');
                return;
            }

            $.ajax({
              type:"POST",
              url:"{{ route('palletoutupdate') }}",
              contentType: "application/x-www-form-urlencoded; charset=utf-8;",
              data: {idrec:Reg,valor:Valor,_token:"{{ csrf_token() }}" },
              cache: false,
              dataType:'json',
              timeout: 0,
              error: function( request, error ){alert('Error al ejecutar la transacción');},
              //beforeSend: function(){alert(controlupdate);},
              success: function(resultado)
              {
                RecalculaBultos();

                  /*  if(resultado.Error!=0)
                    {
                        alert('
                        Error al ejecutar la transacción');
                    }else{
                       // w2ui['grid'].set(controlrec, {Referencia:Valor});
                       RecalculaBultos();
                    }*/
                }
            });
        }

        function RecalculaBultos()
        {
            datos=w2ui.grid.records;
            c=0;
            SumaBultos=0;

            for (var i = 0; i < datos.length; i++)
            {
                var obj = datos[i];
                for (var key in obj)
                {
                    var value = obj[key];
                    if(key=='bultoremito')
                    {
                        var value = obj[key];
                        SumaBultos+=value;
                        }
                }
            }

            $("#sumbultos").val(SumaBultos);
        }

        function PDF()
        {
            $.ajax({
                type:"POST",
                url:"{{ route('PDFPalletout') }}",
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

        function setBgColors(){
            $("#bultoremito").addClass("bg_green");
            //$("#grid_grid_column_4").addClass("bg_blue");
        }

    </script>

@endsection
