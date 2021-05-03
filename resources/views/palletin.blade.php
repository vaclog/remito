@extends('plantillabase')
@section('areatrabajo')

    <div class="row">
        <div class="col-6 mb-3">
            <h4>Pallet-In</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-4 align-items-center">
        <form name="frm_cliente" id="frm_cliente" action="{{ route('palletin.filtroform') }}" method="post">
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
                        <label for="fecha_desde">Fecha</label>
                        @error('fecha_desde')<div class="alert alert-danger">El valor de Fecha desde es incorrecto</div>@enderror
                        <div class='input-group'>
                            <input type="text" value="{{ date("m/Y") }}" id="fecha_desde" name="fecha_desde" class="form-control col-3">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="fecha_hasta">Fecha Hasta</label>
                        @error('fecha_hasta')<div class="alert alert-danger">El valor de Fecha hasta es incorrecto</div>@enderror
                        <div class='input-group'>
                            <input type="text" value="{{ date('m/Y') }}" id="fecha_hasta" name="fecha_hasta" class="form-control col-3">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    -->
                    <button type="submit" id="btn_enviar" name="btn_enviar" class="btn btn-primary">Enviar</button>
                </form>
        </div>
    </div>
    <script>
        $( function()
        {
          $("#fecha_desde").datepicker({dateFormat: 'mm/yy'});
         // $("#fecha_hasta").datepicker({dateFormat: 'mm/yy'});
        });
    </script>

@endsection
