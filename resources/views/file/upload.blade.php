@extends('layouts.app')

@section('content')
<div class="container">
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li> {{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- <form action="/upload" enctype="multipart/form-data" method="post">
     {{ csrf_field() }}
     File: <br>
     <input multiple="multiple" name="archivo" type="file">
     <input type="submit" value="Upload">
    </form> --}}
    <upload-component></upload-component>
    
</div>
@endsection