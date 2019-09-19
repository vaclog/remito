<template>
<div class="shadow p-3 mb-5 bg-white rounded">
    <form enctype="multipart/form-data" @submit.prevent="submit">
     
        <div class="form-group">
            <label for="name">Archivo:</label>
            
            <v-file-input label="File input" @change="change" accept=".csv" required></v-file-input>

        </div>
        <v-spacer></v-spacer>
        <div class="form-group">
            <label for="name">Nro Armado:</label>
            <input type="number" 
            v-model="armado" 
            required
            class="form-control display-1"
            />
            <small id="emailHelp" class="form-text text-muted">Ingrese el numero de Armado de VALKIMIA</small>

        </div>
        
        <v-spacer></v-spacer>

        <div class="form-group text-right">
        
            <!-- <input class="btn btn-secondary" type="submit"  value="Upload"> -->
            
            <v-btn block color="primary" @click="submit" :loading="loading">Procesar</v-btn>
        </div>
        <v-spacer></v-spacer>
        <ul v-if="problema">

            <li class="red lighten-1 white--text text--darken-2 caption" v-for="error of errors" :key="error.id">
            {{error}}
            </li>
        </ul>
        <div class="form-group">
        
                <v-text-field 
                    class="display-1 text-center"
                    v-model="remito"
                    @change="remitoChange"
                    label="Proximo Numero de Remito"
                    required
                    v-mask="'########'"
                    
                    

                    
                ></v-text-field>
                <input type="date" v-model="fecha" @change="changeFechaRemito" class="display-1"/>
        </div>
    </form>
    <div>

        <index-item></index-item>
        
        
    </div>

    
    
</div>
        
    
</template>

<script>

import { mapState } from 'vuex'
import { mapMutations } from 'vuex'

import { mask } from 'vue-the-mask'
import moment from 'moment';

var today = moment().format('YYYY-MM-DD')

    export default {
        directives: { mask},
        data(){
            return {
                fecha: today ,
                loading: false,
                archivo: null,
                armado: 0,
                errors: {'message': 'Hola'},
                items: [],
                remito: 0,
                
                problema: false
              
            }
        },
        methods: {

            remitoChange(remito){
                this.setNumeroRemito(remito)
            },
            ...mapMutations(
                [
                    'setArticulos', 'setNumeroRemito', 'setFechaRemito', 'setCustomer', 
                    'setPedido',
                ]
            ),

            changeFechaRemito(fecha){
                var d = moment(fecha.target.value)
                if (d.isValid()){
                    this.setFechaRemito(fecha.target.value)
                }
            },
            change(file){
                this.archivo = file;
                
            },
            submit(e){
                this.loading = true

                // handle login
                setTimeout(() => {
                    console.log(this)
                }, 5)

                this.problema = false;
                let formData = new FormData();
                formData.append('archivo', this.archivo);
                formData.append('client_id', this.$store.state.idcliente);
                formData.append('armado', this.armado);


                axios.post('/upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                            // JSON responses are automatically parsed.
                            this.items = response.data.articulos
                            this.remito = response.data.numero_remito
                            this.setNumeroRemito(response.data.numero_remito)
                            this.setArticulos(response.data.articulos)
                            this.setCustomer(response.data.customer)
                            this.setPedido(response.data.pedido)
                            this.loading = false;
                            })
                    
                    .catch(error => {
                        this.loading = false;
                        this.problema = true;
                        if (error.response.status === 422) {
                            this.errors = error.response.data.error || {};
                            }
                        else{ 
                            console.log(error.response.data.error);
                            this.errors = error.response.data.error || {};
                        }
                    });       
            }
        },
        mounted() {
            this.setFechaRemito(this.fecha)
            
        },
        computed:
            mapState(['count', 'articulos', 'fecha_remito', 'numero_remito', 'idcliente' ,'customer',
                     'pedido']),
    }
</script>
