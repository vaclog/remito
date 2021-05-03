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
            <h3>Pallet-In</h3>
            <h3>Cliente: {{ Session('RazonSocial') }}</h3>
            <h5>Fecha: {{ $FechaLabelDesde }}</h5>
            <h5><a href="javascript:PDF();"><i class='far fa-file-pdf' style='font-size:24px;color:red'></i></a></h5>
        </div>

        <div class="col-6 text-right">
            <h5>Cant: <input id="sumcant" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumCant }}"/></h5>
            <h5>Pallet In: <input id="sumpalletin" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumPalletin }}"/></h5>
            <h5>Precio Pallet: <input id="sumprecio" type="text" class="text-right small" style="width: 100px;" readonly value=" {{ $SumPrecio }}"/></h5>
            <h5>Picking dev: <input id="sumpiking" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumPicking }}"/></h5>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert" style="display: none;" id="mialerta">
                ¡El cliente no tiene cargadas o activas tarifas en su catalogo de Costos!
            </div>
        </div>
    </div>

    <input type="hidden" id="errormsg" name="errormsg" value="0">
    <div id="grid" style="width: 100%; height: 500px;"></div>

    <script type="text/javascript" src="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />

    @foreach ($Tarifas as $item)
        @if($item->control!=2):
            <script>$('#errormsg').val(1);</script>
        @endif
    @endforeach

    <script type="text/javascript">
        var controlrec=0;
        var sel_record;
        var _NoEditar=$('#errormsg').val();
        var _Operacion={{$Estado}};
        if($('#errormsg').val()==1){$('#mialerta').fadeIn('slow');}

        var tipooperacion = [{ id: 1, text: 'Nuevo' },{ id: 2, text: 'Devolucion' }];

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
                    { field: 'cliente_id', caption: 'Cliente', size: '80px', sortable: true, resizable: true,hidden:true},
                    { field: 'fecha_altaes', caption: 'Fecha', size: '80px', sortable: true, resizable: true},
                    { field: 'documento', caption: 'Nro. Documento', size: '80px', sortable: true, resizable: true},
                    { field: 'NroTareaIR', caption: 'N° de Tarea/IR', size: '80px', sortable: true, resizable: true},
                    { field: 'proveedor_nombre', caption: 'Proveedor', size: '250px', sortable: true, resizable: true},
                    { field: 'Referencia', caption: 'Referencia', size: '250px', sortable: true, resizable: true,editable: { type: 'text' },style: 'text-align: left'},
                    { field: 'IDTipoOperacion', caption: '<b>Tipo Operacion</b>', size: '110px', sortable: true, resizable: true,
                        editable: { type: 'select', items: tipooperacion },
                        render: function (record, index, col_index) {
                            var html = '';
                            for (var p in tipooperacion) {
                                if (tipooperacion[p].id == this.getCellValue(index, col_index)) html = tipooperacion[p].text;
                            }
                            return html;
                        }
                    },
                    { field: 'cant_recibida_unidad', caption: 'Cantidad', size: '120px', sortable: true, resizable: true, style: 'text-align: right'},
                    { field: 'CantidadPalletIN', caption: '<b>Pallet In</b>', size: '90px', sortable: true, resizable: true,render:'number',editable:{type: 'int'}, style: 'text-align: right'},
                    { field: 'PrecioPalletIN', caption: 'Precio Pallet', size: '110px', sortable: true, resizable: true,render:'number',style:'text-align: right'},
                    { field: 'PickingDev', caption: 'Picking Dev', size: '90px', sortable: true, resizable: true, style: 'text-align: right'}
                ],
                records:<?php echo($DSRemitosJson);?>,
                onClick: function(event) {
                    event.onComplete = function(event) {
                        id = event.recid;
                        datos=w2ui.grid.records;
                        for (var i = 0; i < datos.length; i++)
                        {
                            var obj = datos[i];
                            for (var key in obj)
                            {
                                if(key=='recid')
                                {
                                    var value = obj[key];
                                    if(value==id)
                                    {
                                        var combo=obj['IDTipoOperacion'];
                                        if(event.column==9 && combo==2)
                                        {
                                            alert("No se permite cambiar cantidad en modo Devolucion");
                                            w2ui['grid'].save();
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                },
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
                        if (event.column == 9) {
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
                                        CalcularPalletin(cnt,idregistro,1); //1=Indica que es para las cantidades la actialuzacion
                                        w2ui['grid'].save();
                                    }
                                }
                            }
                        }else if(event.column==6){
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
                                        referencia=value; //Es lo que escribe el usuario
                                        v=0;
                                    }
                                }
                            }
                            if(referencia!='')
                            {
                                if(_NoEditar==1)
                                {
                                    alert('¡No se pueden editar registros sin tarifas activas!');
                                    w2ui['grid'].save();
                                    w2ui['grid'].set(controlrec, {Referencia:''});
                                }else{
                                    CalcularPalletin(referencia,idregistro,0);   //Indica que se actualizara la referencia
                                    w2ui['grid'].save();
                                }
                            }

                        }else if(event.column==7){
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
                                        cnt=value; //Es el valor del combo
                                        v=0;
                                    }
                                } //for
                            } // for

                            if(_NoEditar==1)
                            {
                                alert('¡No se pueden editar registros sin tarifas activas!');
                                w2ui['grid'].save();
                            }else{
                                //alert(cnt);
                                CalcularPalletin(cnt,idregistro,2); //2=Indica que es para el combo
                                w2ui['grid'].save();
                            }

                        } //else 7
                    } //event
                }
            });
        });


        function CalcularPalletin(Valor,Reg,Tipo)
        {

            if(_Operacion==1)
            {
                alert('¡La operación esta cerrada, no se puede editar!');
                return;
            }

            $.ajax({
              type:"POST",
              url:"{{ route('palletinupdate') }}",
              contentType: "application/x-www-form-urlencoded; charset=utf-8;",
              data: {idrec:Reg,valor:Valor,tipo:Tipo,_token:"{{ csrf_token() }}" },
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
                        //Si solo actualizo la cantidad, ajusto los datos en el grid
                        if(Tipo==1 || Tipo==2){
                            w2ui['grid'].set(controlrec, {PrecioPalletIN:resultado.Cpin,PickingDev:resultado.Cpkd});
                        }else{
                            w2ui['grid'].set(controlrec, {Referencia:Valor});
                        }
                        RecalculaGrid();
                        //w2ui['grid'].save();
                    }
                }
            });
        }

        function RecalculaGrid()
        {
            datos=w2ui.grid.records;
            c=0;
            SumaCant=0;SumaPalletin=0;SumaPrecio=0;SumaPiking=0;
            for (var i = 0; i < datos.length; i++)
            {
                var obj = datos[i];
                c=0;
                for (var key in obj)
                {
                    if(c>=5 && c<=8)
                    {
                        var value = obj[key];
                        switch(c)
                        {
                            case 5:
                                SumaCant+=parseFloat(value);
                                //console.log('############## ' + value);
                                break;
                            case 6:
                                SumaPalletin+=parseFloat(value);
                                break;
                            case 7:
                                SumaPrecio+=parseFloat(value);
                                break;
                            case 8:
                                SumaPiking+=parseFloat(value);
                                break;
                        }

                        //console.log(value);
                    }
                    c+=1;
                }
            }
            //console.log('>>>>>>>>>>>>>>>>>>>>>>' + SumaCant);
            $("#sumcant").val(Number(SumaCant).toFixed(2));
            $("#sumpalletin").val(Number(SumaPalletin).toFixed(2));
            $("#sumprecio").val(Number(SumaPrecio).toFixed(2));
            $("#sumpiking").val(Number(SumaPiking).toFixed(2));
        }

        function PDF()
        {
            $.ajax({
                type:"POST",
                url:"{{ route('PDFPalletin') }}",
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
