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
                    <th>Marca</th>
                    <th>Cantidad</th>
                
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in articulos" :key="item.id">
                <!-- <td>{{item.id}}</td> -->
                
                <td  >{{item.articulo}}</td>
                <td v-bind:class="['alert',(item.descripcion === 'No encontrado')?'alert-danger':'']">{{item.descripcion}}</td>
                <td>{{item.marca}}</td>
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
            console.log('Index Item Component mounted.')
        },
  computed:
            mapState(['count', 'articulos']),
}
</script>
