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
            <h2>Products Management</h2>
        </div>
        <div class="pull-right">
        
            <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
        
        </div>
        <form action="{{ route('products.index')}}" method="GET" role="search">
           
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
    @foreach ($products as $key => $product)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $product->codigo }}</td>
        <td>{{ $product->descripcion }}</td>
        <td>{{ $product->marca }}</td>
        <td>{{ ($product->disabled)?'SI':'NO'}}</td>
        <td>{{  ($product->client_id)?$product->client->razon_social:'-'}}</td>
        <td>
            
            @can('role-edit')
                <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
            @endcan
            
        </td>
    </tr>
    @endforeach
</table>


{!! $products->render() !!}
                </div>
            </div>
        </div>
    </div>
                
                
@endsection
@section('myjsfiles')
@endsection
