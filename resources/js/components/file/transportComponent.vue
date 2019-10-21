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

            <div class="form-group">
                <v-text-field
                    :value="pedido"
                    
                   
                    
                    label="Nro Pedido"
                    
                   :disabled="true"
                ></v-text-field>
            </div>
            <div class="form-group">
                <v-text-field
                    v-model="remito.observaciones"
                   
                    
                    label="Observaciones"
                    
                   
                ></v-text-field>
            </div>
            <div class="form-group text-right">
    
        <!-- <input class="btn btn-secondary" type="submit"  value="Upload"> -->
             <v-spacer></v-spacer>
            <v-btn block color="primary" @click="submit" 
            :disabled="submitted"
            :loading="loading">{{button_label}}</v-btn>
           
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
        
        button_label: 'Generar Remito',
        editing: false,
        snackbar: false,
        remito: {
            numero_remito: '',
            fecha_remito: '',
            observaciones: '',
            referencia: '',

            client_id: '',
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
        submitted: false,
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

     

    methods:{
        volver(){
            this.$router.back();
        },
        
        ...mapMutations(
            [
                'setArticulos', 'setNumeroRemito', 'setFechaRemito', 'setCustomer', 
                'setPedido',
            ]
        ),

        

        
        
        submit(e){
                this.loading = true
                // handle login
                setTimeout(() => {
             
                }, 5)
                this.remito.customer = this.customer
                this.remito.referencia = this.$store.state.pedido
                
                this.remito.articulos = this.articulos
                this.remito.numero_remito = this.$store.state.numero_remito
                this.remito.fecha_remito = this.$store.state.fecha_remito
                this.remito.client_id = this.$store.state.idcliente
                var RemitoData = this.remito;
                

                axios.post('/remitos/store',
                    RemitoData,
                    )
                    .then(response => {
                            // JSON responses are automatically parsed.
                            this.loading = false;
                            this.submitted = true;
                            this.button_label = 'Remito YA GENERADO';
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
    },


    mounted() {
        
        
            
    },

    beforeMount(){
       
       
        
    },
    
    
    computed:
        mapState(['count', 'articulos', 'customer', 'numero_remito', 'fecha_remito', 'pedido']),
        setItems(){
            this.setArticulos(this.items)
        },
        
        
      
  }

  
</script>
