@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Remitos </h2>
            </div>
            <div class="text-right p2">

                <a class="btn btn-success" href="{{ route('remitos.create') }}"> Crear un Remito</a>

            </div>
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
@endsection