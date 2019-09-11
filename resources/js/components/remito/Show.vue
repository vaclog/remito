<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-5">
                 <v-text-field
                v-model="nro_remito"
                label="Remito Nº"
                readonly
                outlined
                shaped
                class=""
                ></v-text-field>
                
            </div>
            <div class="col-5">
                <v-text-field
                v-model="fecha"
                label="Fecha"
                readonly
                outlined
                shaped
                ></v-text-field>            
            </div>
            <div class="col-2 align-center">
                <div class="align-center">
           
                    <button  @click="imprimir" class="form-control btn btn-success">Imprimir</button>
                     <a href="/home" class="form-control btn btn-brand">Volver</a>
                      
                    
           
                </div>
                
            </div>
        </div>
        

        <div class="row">
            
            <div class="col-3">
                
                <v-text-field
                v-model="customer.nombre"
                label="Cliente"
                readonly
                outlined
                shaped

                ></v-text-field>
                
            </div>
            <div class="col-2">
                <v-text-field
                    v-model="customer.cuit"
                    label="CUIT"
                    readonly
                    outlined
                    shaped
                    ></v-text-field>
            </div>
            <div class="col-3">
                <v-text-field
                    v-model="customer.calle"
                    label="Calle"
                    readonly
                    outlined
                    shaped
                    ></v-text-field>
            </div>
            <div class="col-2">
                <v-text-field
                    v-model="customer.localidad"
                    label="Localidad"
                    readonly
                    outlined
                    shaped
                    ></v-text-field>
            </div>
            <div class="col-2">
                <v-text-field
                    v-model="customer.provincia"
                    label="Provincia"
                    readonly
                    outlined
                    shaped
                    ></v-text-field>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <v-text-field
                    v-model="transport.nombre"
                    label="Transporte"
                    readonly
                    outlined
                    shaped
        
                    ></v-text-field>
            </div>
            <div class="col">
                <v-text-field
                    v-model="transport.conductor"
                    label="Conductor"
                    readonly
                    outlined
                    shaped
                    ></v-text-field>
            </div>
            <div class="col">
                <v-text-field
                    v-model="transport.patente"
                    label="Patente"
                    readonly
                    outlined
                    shaped
                    ></v-text-field>
            </div>
           
            
        </div>
        <div class="row">
            <div class="col">
                <v-text-field
                    v-model="observaciones"
                    label="Observaciones"
                    readonly
                    outlined
                    shaped
        
                    ></v-text-field>
            </div>
        </div>
        
    
        
        

    

    <index-item></index-item>
        
    

    </div>


    
</template>
<script>


import { mapState } from 'vuex'
import { mapMutations } from 'vuex'

import { mask } from 'vue-the-mask'

import moment from 'moment';
export default {
    name: 'show',
    props: {
      
        id: null
    },
    data() {
        return {

            loading: false,
            nro_remito:0,
            sucursal: '',
            remito_id: '',
            fecha: '',
            transport: [],
            customer: [],
            error_msg: null,
            error_flg: false,
            observaciones: ''
        }
    },

    methods:{
        ...mapMutations(
                [
                    'setArticulos', 'setNumeroRemito', 'setFechaRemito'
                ]
            ),

        getRemito(id) {
            axios.get('/api/remito?id=' +id)
                .then((response) => {
                   
                    console.log(response.data)
                   
                    this.transport = {
                        'nombre': response.data.transporte,
                        'conductor': response.data.conductor,
                        'patente': response.data.patente
                    }

                    this.customer = response.data.customer
                    this.customer.calle = response.data.calle
                    this.customer.localidad = response.data.localidad
                    this.customer.provincia = response.data.provincia
                    this.observaciones = response.data.observaciones
                    this.sucursal = response.data.sucursal
                    this.sucursal = this.sucursal.toString().padStart(4,'0')
                    this.remito_id = response.data.numero_remito.toString().padStart(8, '0')
                    console.log(response.data.fecha_remito)
                    this.fecha = moment(response.data.fecha_remito).format('DD/MM/YYYY')  
                    this.nro_remito = this.sucursal + ' - ' + this.remito_id
//                    this.articulos = response.data.articulos
console.log(response.data.articulos)
                    this.setArticulos(response.data.articulos)

                    this.observaciones = response.data.observaciones
                    
                }).catch((e) => {
                    console.log(e)
                });
            },
        imprimir(e){
           window.open('/api/remito/print?id=' + this.id)
            

            
        }
    },

    mounted(){

        console.log('mounted')
        this.getRemito(this.id)
    },
    computed:
            mapState(['count', 'articulos', 'fecha_remito', 'numero_remito']),
            
    
}
</script>



