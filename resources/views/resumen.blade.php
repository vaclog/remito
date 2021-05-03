@extends('plantillabase')
@section('areatrabajo')
<div class="row">
    <div class="col-6 mb-3">
        <h4>Resumen</h4>
    </div>
</div>

<div class="row">
    <div class="col-4 align-items-center">
    <form name="frm_resumen" id="frm_resumen" action="{{ route('resumen.filtroform') }}" method="post">
        @csrf
                <div class="form-group">
                    <label for="cbo_clientes">Cliente</label>
                    <select class="form-control" id="cbo_clientes" name="cbo_clientes">
                        @foreach ($clientes as $item)
                            <option value="{{ $item->id }}">{{ $item->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_resumen">Fecha desde (solo se toma en cuenta Mes y Año)</label>
                    @error('fecha_resumen')<div class="alert alert-danger">El valor de Fecha desde es incorrecto</div>@enderror
                    <div class='input-group'>
                        <input type="text" value="{{ date("m/Y") }}" id="fecha_resumen" name="fecha_resumen" class="form-control col-3" style="max-width: 150px;">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <button type="submit" id="btn_enviar" name="btn_enviar" class="btn btn-primary">Enviar</button>
            </form>
    </div>
</div>
<script>
    $( function()
    {
      $("#fecha_resumen").datepicker({dateFormat: 'mm/yy'});
    });
</script>
@endsection
