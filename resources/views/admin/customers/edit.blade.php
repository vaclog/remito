@section('title', 'Customers')
@extends ('layouts.admin')


@section ('content')
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
    <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver  m-container m-container--responsive m-container--xxl m-page__container">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">

            <div class="m-content">


                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Edit Product</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary" href="{{ route('customers.index') }}"> Back</a>
                        </div>
                    </div>
                </div>


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif


                {!! Form::model($customer, ['method' => 'PATCH','route' => ['customers.update', $customer->id]]) !!}
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Codigo:</strong>
                            {!! Form::text('codigo', null, array('placeholder' => 'Codigo no se puede editar','class' => 'form-control' , 'readonly')) !!}
                            <small class="text-warning">Para cambiar del codigo debe desactivar este registro y crear uno nuevo con otro codigo</small>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Codigo VALKIMIA:</strong>
                            {!! Form::text('codigo_valkimia', null, array('placeholder' => 'Codigo VALKIMIA','class' => 'form-control')) !!}
                        </div>
                    </div>
            
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {!! Form::text('nombre', null, array('placeholder' => 'Descripcion','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>CUIT:</strong>
                            {!! Form::text('cuit', null, array('placeholder' => 'CUIT','class' => 'form-control')) !!}
                        </div>
                    </div>
        
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Calle y Numero:</strong>
                            {!! Form::text('calle', null, array('placeholder' => 'Calle y Numero','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Localidad:</strong>
                            {!! Form::text('localidad', null, array('placeholder' => 'Marca','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Provincia:</strong>
                            {!! Form::text('provincia', null, array('placeholder' => 'Marca','class' => 'form-control')) !!}
                        </div>
                    </div>
        
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Client:</strong>
                            {!! Form::select('client_id', $clients, $customer->client_id, array('value' => $customer->client_id, 'placeholder' => 'Clients', 'class' => 'form-control m-select2')) !!}
                        </div>
                    </div>
            
                    
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Desactivado:</strong>
                            
                            {!! Form::checkbox('disabled', null, $customer->disabled, array('placeholder' => 'Inactive','class' => 'form-control')) !!}
                            
                        </div>
                    </div>
            
            
            
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('myjsfiles')
@endsection
