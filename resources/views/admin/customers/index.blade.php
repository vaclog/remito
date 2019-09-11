@section('title', 'Clientes')
@extends ('layouts.admin')


@section ('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Customers Management</h2>
            </div>
            <div class="text-right p-2" >
            
                <a class="btn btn-success" href="{{ route('customers.create') }}"> Create New Customer</a>
            
            </div>
            <form class="p-2" action="{{ route('customers.index')}}" method="GET" role="search">
                <div class="input-group mb-2">
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
            
            <th>Codigo</th>
            <th>Nombre</th>
            <th>CUIT</th>
            <th>Calle</th>
            <th>Localidad</th>
            <th>Provincia</th>
            <th>Disabled</th>
            <th>Deposito</th>

            <th width="100px">Action</th>
        </tr>
            @foreach ($customers as $key => $customer)
            <tr>
                
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
                        <a class="btn btn-primary btn-sm" href="{{ route('customers.edit',$customer->id) }}">Edit</a>
                    @endcan
                    
                </td>
            </tr>
            @endforeach
        </table>


        {!! $customers->render() !!}
</div>
               
                
@endsection
@section('myjsfiles')
@endsection
