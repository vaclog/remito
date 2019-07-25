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
            <h2>Customers Management</h2>
        </div>
        <div class="pull-right">
        
            <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Customer</a>
        
        </div>
        <form action="{{ route('customers.index')}}" method="GET" role="search">
            <div class="input-group">
                <input type="text" class="form-control" name="buscar"
                    placeholder="Buscar"> 
                    
                    
                    <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">
                        <span class="flaticon-search-1"></span>
                    </button>
                </span>
                
            </div>
        </form>
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
     <th>Codigo</th>
     <th>Descripcion</th>
     <th>Marca</th>
     <th>Disabled</th>
     <th>Cliente</th>
     <th width="280px">Action</th>
  </tr>
    @foreach ($customers as $key => $customer)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $customer->codigo }}</td>
        <td>{{ $customer->nombre }}</td>
        <td>{{ $customer->cuit }}</td>
        <td>{{ $customer->calle }}</td>
        <td>{{ $customer->localidad }}</td>
        <td>{{ $customer->provincia }}</td>
        
        <td>{{ ($customer->disabled)?'SI':'NO'}}</td>
        <td>{{  ($customer->client_id)?$customer->client->razon_social:'-'}}</td>
        <td>
            
            @can('role-edit')
                <a class="btn btn-primary" href="{{ route('customers.edit',$customer->id) }}">Edit</a>
            @endcan
            
        </td>
    </tr>
    @endforeach
</table>


{!! $customers->render() !!}
                </div>
            </div>
        </div>
    </div>
                
                
@endsection
@section('myjsfiles')
@endsection
