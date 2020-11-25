@extends('plantillabase')
@section('areatrabajo')

<div class="row">
    <div class="col-6 mb-3">
        <h4>Costos</h4>
    </div>
</div>
<div class="row"><div class="col-12"> <h2>Seleccione el cliente para continuar.</h2></div></div>
<div class="row">
    <div class="col-4 align-items-center">
            <form name="frm_cliente" id="frm_cliente" action="{{ route('Costos.CargaCliente') }}" method="post">
            @csrf
                <div class="form-group">
                    <label for="cbo_clientes">Cliente</label>
                    <select class="form-control" id="cbo_clientes" name="cbo_clientes">
                        @foreach ($clientes as $item)
                            <option value="{{ $item->id }}">{{ $item->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" id="btn_enviar" name="btn_enviar" class="btn btn-primary">Cargar</button>
            </form>
    </div>
</div>

@endsection
