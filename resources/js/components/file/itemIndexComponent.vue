<template>
    <div class="shadow p-3 mb-5 bg-white rounded">
        <strong class="display-1">Items</strong>
        <v-spacer></v-spacer>

        <div class="row">
        <table id="firstTable" class="table">
            <thead>
                <tr>
                    <th>Articulo</th>
                    <th>Descripion</th>
                    <th>Ean13</th>
                    <th>Lote</th>
                    <th>Fec. Vto</th>
                    <th>UM</th>
                    <th>Cantidad</th>
                
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in articulos" :key="item.id">
                <!-- <td>{{item.id}}</td> -->
                
                <td>{{item.codigo}}</td>
                <td v-bind:class="['alert',(item.descripcion === 'No encontrado')?'alert-danger':'']">{{item.descripcion}}</td>
                <td>{{item.ean13}}</td>
                <td>{{item.lote}}</td>
                <td>{{item.fecha_vencimiento}}</td>
                <td>{{item.unidad_medida}}</td>

                <td class="text-right">{{item.cantidad}}</td>
              
                </tr>
            </tbody>
        </table>
        </div>
      
   
    </div>
</template>
<script>
import { mapState } from 'vuex'

export default {
   
     mounted() {
          
        },
  computed:
            mapState(['count', 'articulos']),
}
</script>
