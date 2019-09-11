@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <h1>{{$client->razon_social}}</h1>
        <input type="hidden" name="client_id" id="client_id" value="{{ $client->id}}">
        <home></home>

        
    </div>
</div>
@endsection
