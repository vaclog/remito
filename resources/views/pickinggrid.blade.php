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
            <h3>Picking</h3>
            <h3>Cliente: {{ Session('RazonSocial') }}</h3>
            <h5>Fecha: {{ $FechaLabelDesde }}</h5>
            <h5><a href="javascript:PDF();"><i class='far fa-file-pdf' style='font-size:24px;color:red'></i></a></h5>
        </div>
        <div class="col-6 text-right">
            <h5>Tot.Unid.Pickeadas: <input id="sumbultos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumTotUnidPickeadas }}"/></h5>
            <h5>Tot.Cajas c/Dec: <input id="sumbultos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumTotCajasDecimales }}"/></h5>
            <h5>Tot.Cajas: <input id="sumbultos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumTotCajas }}"/></h5>
            <h5>Tot.Unidades: <input id="sumbultos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumUnidades }}"/></h5>
            <h5>Tot.Pickeadas: <input id="sumbultos" type="text" class="text-right small" style="width: 100px;" readonly value="{{ $SumTotPickeadas }}"/></h5>
        </div>
    </div>

    <div id="grid" style="width: 100%; height: 450px;"></div>

    <script type="text/javascript" src="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />

    <script type="text/javascript">
        var controlrec=0;
        var sel_record;
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
                    { field: 'recid', caption: 'ID', size: '50px', sortable: true, resizable: true,hidden:true },
                    { field: 'cliente_id', caption: 'ID.Cliente',hidden:true, size: '50px', sortable: true, resizable: true },
                    { field: 'articulo_codigo', caption: 'Artículo', size: '250px', sortable: true, resizable: true,style: 'text-align: left'},
                    { field: 'cant_documento_unidad', caption: 'Pedida', size: '80px', sortable: true, resizable: true, style: 'text-align: left'},
                    { field: 'documento', caption: 'Delivery', size: '80px', sortable: true, resizable: true, style: 'text-align: left'},
                    { field: 'master', caption: 'Master', size: '80px', sortable: true, resizable: true, style: 'text-align: right'},
                    { field: 'cant_preparada_unidad', caption: 'Unidades Pickeadas', size: '140px', sortable: true, resizable: true, style: 'text-align: right'},
                    { field: 'fecha_altaes', caption: 'Fecha alta', size: '80px', sortable: true, resizable: true,style: 'text-align: left',hidden:true},
                    { field: 'ColCajaDecimales', caption: 'Caja/Dec',render:{type: 'number:2'}, size: '80px', sortable: true, resizable: true,style: 'text-align:right'},
                    { field: 'ColCajas', caption: 'Cajas', size: '80px', sortable: true, resizable: true,style: 'text-align:right'},
                    { field: 'ColUnidades', caption: 'Unidades', size: '80px', sortable: true, resizable: true,style: 'text-align:right'},
                    { field: 'ColPickeadas', caption: 'Pickeadas', size: '80px', sortable: true, resizable: true,style: 'text-align:right'}
                ],
                records:<?php echo($DSPickingJson);?>
            });
        });


        function PDF()
        {
            $.ajax({
                type:"POST",
                url:"{{ route('PDFPickingdev') }}",
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
