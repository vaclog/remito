@section('title', 'Clientes')
@extends ('layouts.admin')


@section ('content')

<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
        <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver  m-container m-container--responsive m-container--xxl m-page__container">
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
    
                <div class="m-content">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Clientes Management</h2>
        </div>
        <div class="pull-right">
        
            <a class="btn btn-success" href="{{ route('clients.create') }}"> Create New Cliente</a>
        
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif


<table class="table table-bordered">
  <tr>
     <th>No</th>
     <th>Name</th>
     <th>Sucursal</th>
     <th>CAI Nro</th>
     <th>CAI Vencimiento</th>
     <th>Disabled</th>
     <th width="280px">Action</th>
  </tr>
    @foreach ($clients as $key => $client)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $client->razon_social }}</td>
        <td>{{ str_pad( $client->sucursal, 4, "0", STR_PAD_LEFT) }}</td>
        <td>{{ $client->cai }} </td>
        <td style="text-align: center">{{ (strtotime($client->cai_vencimiento)> 0)?date_format(date_create($client->cai_vencimiento), 'd/m/Y'):'' }}</td>
        <td style="text-align: center">{{ ($client->disabled)?'SI':'NO' }}</td>
        <td>
            
            @can('role-edit')
                <a class="btn btn-primary" href="{{ route('clients.edit',$client->id) }}">Edit</a>
            @endcan
            {{-- @can('role-delete')
                {!! Form::open(['method' => 'DELETE','route' => ['clients.destroy', $client->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            @endcan --}}
        </td>
    </tr>
    @endforeach
</table>


{!! $clients->render() !!}
                </div>
            </div>
        </div>
    </div>
                
                
@endsection
@section('myjsfiles')
@endsection
