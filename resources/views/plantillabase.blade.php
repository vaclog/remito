<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>VACLOG WD</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- CSS -->
        <link href="css/app.css" rel="stylesheet"  type="text/css">
        <link href="css/menu.css" rel="stylesheet"  type="text/css">
        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <!-- Java -->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>

    </head>
    <body>
        <div class="container-fluid pt-3 text-white" style="background-color: white;">
            <div class="pt-1 pb-4" style="background-color: white;">
                <img src="images/VACLOG.jpg" width="200px;">
                <!--<h2>REMITOS VACLOG WD - {{ ucfirst(Route::currentRouteName()) }}</h2>-->
            </div>
        </div>
        <div class="topnav">
            @if (Route::currentRouteName() != 'home')
                <a href="{{route('home')}}">Home</a>
                <a href="{{url()->previous()}}">Volver</a>
            @else
                <a href="{{route('resumen')}}">Resumen</a>
                <a href="{{route('palletin')}}">Pallet-In</a>
                <a href="{{route('palletout')}}">Pallet-Out</a>
                <a href="{{route('picking')}}">Picking</a>
                <a href="{{route('almacenaje')}}">Almacenaje</a>
                <a href="{{route('costos')}}">Costos</a>

            @endif
        </div>
        <div class="container-fluid pt-5">
            @yield('areatrabajo')
        </div>
    </body>
</html>
