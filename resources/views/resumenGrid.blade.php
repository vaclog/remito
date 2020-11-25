@extends('plantillabase')
@section('areatrabajo')

<div class="row">
    <div class="col-6 mb-3">
        <h2>Resumen</h2>
    </div>
</div>

@if($Estado!=0)
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-danger" role="alert">OPERACION CERRADA</div>
            </div>
        </div>
    @endif

<div class="row">
    <div class="col-3"><h4>Cliente: {{ Session('RazonSocial') }}</h4></div>
    <div class="col-3"><h4>Mes/Año: {{ $_DatosFecha }}</h4></div>
</div>

<div class="row">
    <div class="col-12 pt-1 pb-1"><h5>Validación de datos:</h5></div>
</div>
<div class="row">
    <div class="col-3">
        @if($_ErrorPI==1)
            <i class="fa fa-check-square-o" style="font-size:20px;color:red;"> Pallet-In</i>
        @else
            <i class="fa fa-check-square-o" style="font-size:20px;color:green;"> Pallet-In</i>
        @endif
    </div>
    <div class="col-3">
        @if($_ErrorPO==1)
        <i class="fa fa-check-square-o" style="font-size:20px;color:red;"> Pallet-Out</i>
        @else
            <i class="fa fa-check-square-o" style="font-size:20px;color:green;"> Pallet-Out</i>
        @endif
    </div>
    <div class="col-3">
        @if($_ErrorPC==1)
            <i class="fa fa-check-square-o" style="font-size:20px;color:red;"> Picking</i>
        @else
            <i class="fa fa-check-square-o" style="font-size:20px;color:green;"> Picking</i>
        @endif
    </div>
    <div class="col-3">
        @if($_ErrorAL==1)
            <i class="fa fa-check-square-o" style="font-size:20px;color:red;"> Almacenaje</i>
        @else
            <i class="fa fa-check-square-o" style="font-size:20px;color:green;"> Almacenaje</i>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-4 mt-5">
        <h5>Items extras<br> (dejar importe en 0 para excluir el item)</h5>
    </div>
</div>
<div class="row">
    <div class="col-5">
        <div id="grid" style="width: 100%; height: 350px;"></div>
    </div>
    <div class="col-3 mr-5">
        <button type="button" id="pdfprevio" name="pdfprevio" class="btn btn-warning" onclick="PdfResumen(1);">Generar PDF previo</button>
    </div>
    <div class="col-3">
        <button type="button" id="cerrar" name="cerrar" class="btn btn-success" onclick="PdfResumen(2);"
        @if($Estado!=0)
            disabled
        @endif >Cerrar resumen y generar PDF</button>
    </div>
</div>


<script type="text/javascript" src="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />


<script type="text/javascript">
    var controlrec=0;
    var sel_record;
    var Cerrar={{$_Editable}};
    var Operacion={{$Estado}};

    $(function () {
        $('#grid').w2grid({
            name: 'grid',
            show: {
                toolbar: true,
                footer: true,
                toolbarSave: false
            },
            columns: [
                { field: 'recid', text: 'ID', size: '50px', sortable: true, resizable: true,hidden:true },
                { field: 'Id_Encabezado', text: 'ID', size: '50px', sortable: true, resizable: true,hidden:true },
                { field: 'Descripcion', text: '<b>Descripción</b>', size: '370px', sortable: true, resizable: true,editable:{type: 'text'}},
                { field: 'Importe', text: '<b>Importe</b>', size: '80px', sortable: true, resizable: true,editable:{type: 'number:2'},render:'number:2',style:'text-align: right'}
            ],
            records:<?php echo($DSItemsJson);?>,
            onChange: function (event) {
                event.onComplete = function() {
                    var grid = this;
                    var sel_rec_ids = grid.getSelection();
                    if (sel_rec_ids.length)
                    {
                        sel_record = grid.get(sel_rec_ids[0]);
                        controlrec=sel_rec_ids[0];
                    }

                    datos=w2ui['grid'].getChanges();
                    var Columna=0;
                    var colDescripcion='';
                    var colImporte=0;
                    var colIxd=0;

                    for (var i = 0; i < datos.length; i++){
                        var obj = datos[i];
                        for (var key in obj)
                        {
                            if(key=='Descripcion')
                            {
                                colDescripcion = obj[key];
                                colIxd=1;
                            }else if(key=='Importe'){
                                colImporte = obj[key];
                                colIxd=2;
                            }
                        }
                    }

                    //Actualizo
                    if($.isNumeric(colImporte))
                    {
                        ActualizarBase(controlrec,colDescripcion,colImporte,colIxd);
                        w2ui['grid'].save();

                    }else{
                        //Cancelo la edicion
                        w2ui['grid'].save();
                        alert('Verifique la columna Importe, solo se aceptan números');
                    }

                } //event
            } //onchange
        }); //grid
    }); //function

    function ActualizarBase(Registro,Descripcion,Importe,Col)
    {
        if(Operacion!=0)
        {
            alert('¡La operación esta cerrada, no se puede editar!');
            return;
        }

        $.ajax({
            type:"POST",
            url:"{{ route('ajax.GrabaResumen') }}",
            contentType: "application/x-www-form-urlencoded; charset=utf-8;",
            data: {idrec:Registro,Desc:Descripcion,Imp:Importe,ColIdx:Col,_token:"{{ csrf_token() }}" },
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
                }
            }
        });
    }

    function PdfResumen(Modo)
    {
        if(Modo==2 && Cerrar>0)
        {
            alert('No se puede cerrar cuando hay items incompletos');
            return;
        }

        if(Modo==2)
        {
            var retVal = confirm("¿ Está seguro de cerrer la prefacturación ?");
            if(retVal!=true){ return;}
        }

        $.ajax({
            type:"POST",
            url:"{{ route('ajax.pdfresumen') }}",
            contentType: "application/x-www-form-urlencoded; charset=utf-8;",
            data: {Accion:Modo,_token:"{{ csrf_token() }}" },
            cache: false,
            dataType:'json',
            timeout: 0,
            error: function( request, error ){alert('Error al ejecutar la transacción' + request.responseText);},
            //beforeSend: function(){alert(controlupdate);},
            success: function(resultado)
            {
                if(resultado.Error!=0)
                {
                    alert('Error al ejecutar la transacción - ' + resultado.Msg);
                }else{
                    window.open(resultado.Path, '_blank');
                    if(Modo==2)
                    {
                        location.href = "{{route('home')}}";
                    }
                }
            }
        });
    }

</script>

@endsection
