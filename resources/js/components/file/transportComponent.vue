<template>
<v-app>
    <v-content>
        <div class="shadow p-3 mb-5 bg-white rounded">
        
            <form  @submit.prevent="submit">
            <div class="form-group">
                <v-text-field 
                      
                    v-model="remito.transportista.transporte"
                    :rules="transporteRules"
                    label="Transporte"
                    required
                    @change="remito.transportista.transporte = remito.transportista.transporte.toUpperCase()"
                    
                ></v-text-field>
            </div>
            <div class="form-group">
                <v-text-field
                    v-model="remito.transportista.conductor"
                    :rules="conductorRules"
                    label="Chofer"
                    required
                    
                    @change="remito.transportista.conductor = remito.transportista.conductor.toUpperCase()"
                ></v-text-field>
            </div>
            <div class="form-group">
                <v-text-field
                    v-model="remito.transportista.patente"
                    :rules="patenteRules"
                    label="Patente"
                    required
                    @change="remito.transportista.patente = remito.transportista.patente.toUpperCase()"
                ></v-text-field>
            </div>
            <div class="form-group text-right">
    
        <!-- <input class="btn btn-secondary" type="submit"  value="Upload"> -->
             <v-spacer></v-spacer>
            <v-btn block color="primary" @click="submit" :loading="loading">Generar Remito</v-btn>
            </div>

                <v-snackbar
                    v-model="snackbar"
                    :timeout=3000
                    color="sucess"
                    >
                    Remito Guardado
                    
                </v-snackbar>
            </form>
        
        </div>
        <v-layout justify-space-around wrap>

            <v-expansion-panels
                :accordion="accordion"
                :popout="popout"
                :inset="inset"
                :multiple="multiple"
                :focusable="focusable"
                :disabled="disabled"
                :readonly="readonly"
                >
                <v-expansion-panel>
                    <v-expansion-panel-header class="title">{{ (customer)?customer.nombre:'' }}</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-layout wrap>
                            <v-flex xs6>
                                <v-card>
                                    <v-card-text class="px-25">
                                        <v-text-field
                                            v-model="customer.cuit"
                                            
                                            label="CUIT"
                                            readonly
                                            disabled
                                            
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                                
                            </v-flex>
                            <v-flex xs6>
                                <v-card >
                                    <v-card-text class="px-25">
                                        <v-text-field
                                            v-model="customer.calle"
                                            
                                            label="Calle"
                                            readonly
                                            disabled
                                            
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                                
                            </v-flex>
                            <v-flex xs6>
                                <v-card >
                                    <v-card-text class="px-25">
                                        <v-text-field
                                            v-model="customer.localidad"
                                            
                                            label="Localidad"
                                            readonly
                                            disabled
                                            
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                                
                            </v-flex>
                            <v-flex xs6>
                                <v-card >
                                    <v-card-text class="px-25">
                                        <v-text-field
                                            v-model="customer.provincia"
                                            
                                            label="Provincia"
                                            readonly
                                            disabled
                                            
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                                
                            </v-flex>

                          </v-layout>
                        
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                    <v-expansion-panel-header class="title">Articulos</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <index-item :items="items"></index-item>
                    </v-expansion-panel-content>
                </v-expansion-panel>
       


            </v-expansion-panels>
            

        </v-layout>
    </v-content>
    
</v-app>
  
</template>
<script>

import { mapState } from 'vuex'
import { mapMutations } from 'vuex'

  export default {
    props: {
        edit: false,
        id: null
    },

    data: () => ({
        editing: false,
        snackbar: false,
        remito: {
            numero_remito: '',
            fecha_remito: '',
            customer: {},
            articulos: {},
            transportista: {
                transporte: '',
                conductor: '', 
                patente: '', 
            }
        },
        items: {},
        accordion: false,
        popout: false,
        loading: false,
        inset: false,
        multiple: false,
        disabled: false,
        readonly: false,
        focusable: false,
        selectedcustomer: [],
        
        transporteRules: [
                    v => !!v || 'Transporte is required',
                    
                ],
        
        conductorRules: [
                    v => !!v || 'Conductor is required',
                    
                ],
        
        patenteRules: [
                    v => !!v || 'Patente is required',
                    
                ],
    }),

     ...mapMutations(
                [
                    'setArticulos', 'setNumeroRemito', 'getRemito'
                ]
            ),

    methods:{

        getRemito(id) {
            axios.get('/api/remito?id=' +id)
                    .then((response) => {
                        //this.remito = response.data;
                        console.log(response.data)
                        this.items = response.data.articulos;
                        console.log(response.data.articulos)
                    }).catch((e) => {
                        console.log(e)
                    }).then((data) => {
                        //console.log(this.items)
                        //this.setArticulos(this.items)
                    });
            },
        
        submit(e){
                this.loading = true
                // handle login
                setTimeout(() => {
                    console.log(this)
                }, 5)
                this.remito.customer = this.customer
                this.remito.articulos = this.articulos
                this.remito.numero_remito = this.$store.state.numero_remito
                this.remito.fecha_remito = this.$store.state.fecha_remito
                console.log(this.$store.state.fecha_remito)
                var RemitoData = this.remito;
                

                axios.post('/remitos/store',
                    RemitoData,
                    )
                    .then(response => {
                            // JSON responses are automatically parsed.
                           console.log(response.data);
                            this.loading = false;
                            this.snackbar = true;
                            })
                    
                    .catch(error => {
                        this.loading = false;
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                            }
                        else{ 
                            this.errors = error.response.data.errors || {};
                        }
                    });
                    
            }
    },

    created(){
        console.log('creado')
    },


    mounted() {
        
        if (this.edit){
            console.log(' mounted')
            console.log(this.items)
           // this.setArticulos(this.items)
        }
            
    },

    beforeMount(){
        if (this.edit){
            console.log(' Before mount')
            this.getRemito(this.id)
         
        }
        
    },
    
    
    computed:
        mapState(['count', 'articulos', 'customer', 'numero_remito', 'fecha_remito']),
        setItems(){
            this.setArticulos(this.items)
        }

  }

  
</script>
