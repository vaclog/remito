@section('title', 'Clients')
@extends ('layouts.admin')


@section ('content')
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
        <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver  m-container m-container--responsive m-container--xxl m-page__container">
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
    
                <div class="m-content">


<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Client</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('clients.index') }}"> Back</a>
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


{!! Form::model($client, ['method' => 'PATCH','route' => ['clients.update', $client->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Razon Social:</strong>
            {!! Form::text('razon_social', null, array('placeholder' => 'Nombre de la empresa','class' => 'form-control')) !!}
        </div>

        <div class="form-group col-4">
            <strong>Sucursal:</strong>
            {!! Form::text('sucursal', null, array('placeholder' => 'Sucursal','class' => 'form-control')) !!}
        </div>
        <div class="form-group col-4">
            <strong>Numero CAI:</strong>
            {!! Form::text('cai', null, array('placeholder' => 'Numero CAI','class' => 'form-control')) !!}
        </div>
        <div class="form-group col-4">
                <label for="cai_vencimiento"><strong> Vencimiento CAI</strong></label>

            <div class="input-group date">
                <input type="text" id="cai_vencimiento" name="cai_vencimiento" class="form-control">
                <span class="input-group-addon">
                    <i class="material-icons">calendar_today</i></span>
            </div>
            
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Desactivado:</strong>
            
            {!! Form::checkbox('disabled', null, $client->disabled, array('placeholder' => 'Inactive','class' => 'form-control')) !!}
            
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

<script type="text/javascript">
    $('#cai_vencimiento').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
        todayHighlight: true
    });
</script>
@endsection
