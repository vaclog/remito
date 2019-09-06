@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 margin-tb">
            <div class="pull-left">
                <h2>Remitos </h2>
            </div>
        </div>
        <div class="col-lg-7">
        <form>
            
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label col-form-label-lg">Cliente</label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-lg" id="select_client_id" name="select_cliente_id" placeholder="Seleccione Cliente">
                        @foreach ($clients as $client)
                            
                            <option value="{{ $client->id}}"  {{ $client_selected == $client->id ? "selected" : "" }} >{{$client->razon_social}}</option>
                        @endforeach
                        </select>
                        <input type="hidden" name="client_selected" id="client_selected">

                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary btn-lg">Go</button>
                    </div>
                </div>
          
    
        
        </form>
        </div>
        <div class="col-lg-2 text-right p2">

            <a class="btn btn-success" href="{{ route('remitos.create', [ 'client_id' => $client_selected ]) }}"> Crear un Remito</a>

        </div>
    
    </div>


    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif


    <table class="table table-bordered responsive">
    <tr>
            <th>No Remito</th>
            <th>Fecha<br>Remito</th>
            <th>Cliente</th>
            <th>Direccion entrega</th>
            <th>Transportista</th>
            <th width="280px">Action</th>
    </tr>
        @foreach ($remitos as $key => $remito)
        <tr>  

            <td class="text-center">{{ str_pad( $remito->sucursal, 4, "0", STR_PAD_LEFT).'-'.str_pad( $remito->numero_remito, 8, "0", STR_PAD_LEFT)}}</td>
            <td class="text-center">{{ (strtotime($remito->fecha_remito)> 0)?date_format(date_create($remito->fecha_remito), 'd/m/Y'):'' }}</td>
            <td>{{ $remito->customer->nombre}}</td>
            <td>{{ $remito->calle}} <br> {{$remito->localidad}}<br>{{$remito->provincia}}</td>
            <td >Trans: {{$remito->transporte}}<br>Cond: {{$remito->conductor}}<br>Path: {{$remito->patente}}</td>
            <td>
                
                @can('role-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('remitos.show',$remito->id) }}">Detalle</a>
                    <a class="btn btn-success btn-sm" href="{{ url('/api/remito/print?id='.$remito->id) }}">Imprimir</a>

                @endcan
                    
      
            </td>
        </tr>
        @endforeach
    </table>


    {!! $remitos->render() !!}
</div>
            
            
@endsection
@section('myjsfiles')

    <script>
        @if (!empty($client_selected))
            var ClientId = {{ $client_selected }};
    
        @else
            var ClientId = null;
        @endif
        
        console.log(ClientId);
    
    </script>
    <script src="{{ URL::asset('js/remito/index.js') }}" type="text/javascript"></script>

@endsection