@section('title', 'Products')
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
            <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
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


{!! Form::model($product, ['method' => 'PATCH','route' => ['products.update', $product->id]]) !!}
<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Codigo:</strong>
            {!! Form::text('codigo', null, array('placeholder' => 'Codigo del Producto','class' => 'form-control')) !!}
        </div>
    </div>
    
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Descripción:</strong>
            {!! Form::text('descripcion', null, array('placeholder' => 'Descripcion del producto','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Marca:</strong>
            {!! Form::text('marca', null, array('placeholder' => 'Marca','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Client:</strong>
            {!! Form::select('client_id', $clients, $product->client_id, array('value' => $product->client_id, 'placeholder' => 'Clients', 'class' => 'form-control m-select2')) !!}
        </div>
    </div>
    
    
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Desactivado:</strong>
            
            {!! Form::checkbox('disabled', null, $product->disabled, array('placeholder' => 'Inactive','class' => 'form-control')) !!}
            
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
