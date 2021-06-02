@extends('plantillabase')
@section('areatrabajo')

    <div class="row">
        <div class="col-6 mb-3">
            <h4>Artículos</h4>
            <h3>Cliente: {{ Session('RazonSocial') }}</h3>
        </div>
    </div>

        <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Id Artículo</th>
                <th scope="col">Código artítculo</th>
                <th scope="col">Artículo descripción</th>
                <th scope="col">Master</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($DSListaArticulos as $item)
                <tr>
                    <th scope="row">{{$item->IdArticulo}}</th>
                    <td scope="row">{{$item->articulo_codigo}}</td>
                    <th scope="row">{{$item->articulo_descripcion}}</th>
                    <th scope="row"><input  type="text" maxlength="10" style="width: 5em;" value="{{$item->articulo_master}}" id="{{$item->IdArticulo}}"></th>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col">
                {{ $DSListaArticulos->render() }}
            </div>
        </div>
        <script>
            $(document).ready(function(){
            $('input').bind("enterKey",function(e){
                id=$(e.target).attr("id");
                obj="#" + id;
                valor=$(obj).val();
               if(isNaN(valor)){alert("¡El valor no es numerico!");}else{Guardar(id,valor);}
            });
            $('input').keyup(function(e){
               if(e.keyCode == 13)
               {
                  $(this).trigger("enterKey");
               }
            });
         });

         function Guardar(idpr,nuevovalor)
         {
            $.ajax({
                type:"POST",
                url:"{{ route('articulosup') }}",
                contentType: "application/x-www-form-urlencoded; charset=utf-8;",
                data: {Cliente:{{$idc}},idp:idpr,valor:nuevovalor,_token:"{{ csrf_token() }}"},
                cache: false,
                dataType:'json',
                timeout: 0,
                error: function( request, error ){},
                success: function(resultado){}
            });
         }
        </script>
@endsection
