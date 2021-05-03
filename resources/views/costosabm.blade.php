@extends('plantillabase')
@section('areatrabajo')

<div class="row"><div class="col-12"><h3>ABM - Costos</h3></div></div>
<div class="row"><div class="col-12"> <h3>Cliente: {{ Session('RazonSocial') }}</h3></div></div>

<div class="row">
    <div class="col-5">
        <div id="grid" style="width:100%;height: 400px;"></div>
    </div>

    <div class="col-4" style="border: #cccccc solid 1px;">
        <form id="frm_costos" name="frm_costos">
            @csrf
            <input type="hidden" id="idrec" name="idrec" value="0">
            <input type="hidden" id="accion" name="accion" value="0">
            <input type="hidden" id="controlupdate" name="controlupdate" value="0">
            <div class="form-group">
              <label for="txnombre">Nombre Tarifa:</label>
                <select class="form-control" id="cbo_tarifas" name="cbo_tarifas">
                    @foreach ($DS_Tarifas as $item)
                        <option value="{{ $item->idtarifa }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3 pl-0">
                <label for="txprecio">Precio:</label>
                <input type="text" class="form-control" id="txprecio" name="txprecio" maxlength="12" value="0">
            </div>

            <div class="form-group col-md-3 pl-0">
                <label for="cboactiva">Activa:</label>
                <select id="cbo_activa" name="cbo_activa" class="form-control">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="form-group pt-5">
                <button type="submit" class="btn btn-default mr-5" id="btnguardar" name="btnguardar" disabled  onclick="Update();">Guardar</button>
                <button type="submit" class="btn btn-default" id="btnguardarnuevo" name="btnguardarnuevo" onclick="Nuevo();">Guardar como nuevo</button>
            </div>

          </form>
    </div>
</div>

<script type="text/javascript" src="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
<script src="js/jqvalidate.js"></script>
<link rel="stylesheet" type="text/css" href="http://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />
<script type="text/javascript">
    var controlrec=0;
    var controlupdate=0;

        $(function () {
            $('#grid').w2grid({
                name: 'grid',
                show: {
                    toolbar: true,
                    footer: true,
                    toolbarSave: true
                },
                columns: [
                    { field: 'recid', caption: 'ID', size: '50px', sortable: true, resizable: true },
                    { field: 'NombreTarifa', caption: 'Nombre Tarifa', size: '270px', sortable: true, resizable: true},
                    { field: 'NombreTarifaID', caption: 'IDTarifa', size: '2px', sortable: true, resizable: true},
                    { field: 'Precio', caption: 'Precio', size: '70px', sortable: true, resizable: true,attr: "align=right" },
                    { field: 'Activa', caption: 'Activa', size: '50px', sortable: true, resizable: true,attr: "align=center"}
                ],
                records: <?php echo($DS_CostosJS); ?>,
                onClick: function(event) {
                    var grid = this;
                    event.onComplete = function() {
                        var sel_rec_ids = grid.getSelection();
                        if (sel_rec_ids.length) {
                            var sel_record = grid.get(sel_rec_ids[0]);
                            controlrec=sel_rec_ids[0];
                            controlupdate=sel_record.NombreTarifaID;
                            $('#btnguardar').attr('disabled', false);
                            $('#cbo_tarifas').val(sel_record.NombreTarifaID);
                            $('#txprecio').val(sel_record.Precio);
                            $('#idrec').val(sel_record.recid);
                            if(sel_record.Activa=='No')
                            {
                                $("#cbo_activa").val(0);
                            }else{
                                $("#cbo_activa").val(1);
                            }
                        } else {
                            //Limpio el fomulario
                            Limpiarform();
                            controlrec=0;
                            controlupdate=0;
                        }
                    }
                }
            });

            w2ui['grid'].hideColumn('NombreTarifaID');
        });

$(document).ready(function(){
    $("#frm_costos").validate({
        rules:{txprecio:{required: true,maxlength:12,number: true}},
        messages: {txprecio:"Ingrese un valor numérico para el precio"},
        debug: true,
        submitHandler: function(form)
        {
          $.ajax({
              type:"POST",
              url:"{{ route('costosupdate') }}",
              contentType: "application/x-www-form-urlencoded; charset=utf-8;",
              data:$("#frm_costos").serialize(),
              cache: false,
              dataType:'json',
              timeout: 0,
              error: function( request, error ){console.log();},
              //beforeSend: function(){alert(controlupdate);},
              success: function(resultado)
              {
                if(resultado.Error!=0)
                {
                    alert('Error al ejecutar la transacción');
                  }else{
                    var CboValor='';
                    var CboTarifa='';

                        if($('#accion').val()=='1' && resultado.Msg>0)
                        {
                            if($('#cbo_activa').val()==1){CboValor='Sí';}else{CboValor='No';}
                            w2ui['grid'].add({ recid: resultado.Msg,NombreTarifa:$('#cbo_tarifas option:selected').text(),
                                                Precio:$('#txprecio').val(),Activa:CboValor,NombreTarifaID:$('#cbo_tarifas').val()});
                            Limpiarform();
                        }else if($('#accion').val()=='1' && resultado.Msg<0){
                            alert('¡Ya existe un costo igual!');
                        }else if($('#accion').val()=='2' && resultado.Msg<0){
                            alert('¡Ya existe un costo igual!');
                        }else{
                            if($('#cbo_activa').val()==1){CboValor='Sí';}else{CboValor='No';}
                            w2ui['grid'].set(controlrec,{NombreTarifa:$('#cbo_tarifas option:selected').text(),
                                                Precio:$('#txprecio').val(),Activa:CboValor,NombreTarifaID:$('#cbo_tarifas').val()});
                            Limpiarform();
                        }

                    }
                }
          });
        }
      }); //Validator
});
function Nuevo(){$('#accion').val(1);$('#controlupdate').val('0');}
function Update(){$('#accion').val(2);$('#controlupdate').val(controlupdate);}
function Limpiarform()
{
    $('#cbo_tarifas').val(1);
    $('#txprecio').val('0');
    $('#idrec').val('0');
    $('#accion').val('0');
    $('#controlupdate').val('0');
    $('#btnguardar').attr('disabled', true);
}
</script>
@endsection
