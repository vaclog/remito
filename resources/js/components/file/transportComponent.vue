<template>
<v-app>
    <v-content>
        <v-card>
            <form action="">
            <div class="form-group">
                <v-text-field
                    v-model="transporte"
                    :rules="transporteRules"
                    label="Transporte"
                    required
                    @change="uppercase"
                ></v-text-field>
            </div>
            <div class="form-group">
                <v-text-field
                    v-model="conductor"
                    :rules="conductorRules"
                    label="Chofer"
                    required
                ></v-text-field>
            </div>
            <div class="form-group">
                <v-text-field
                    v-model="patente"
                    :rules="patenteRules"
                    label="Patente"
                    required
                ></v-text-field>
            </div>
            </form>
        </v-card>
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
                        <index-item></index-item>
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
    data: () => ({
        accordion: false,
        popout: false,
        inset: false,
        multiple: false,
        disabled: false,
        readonly: false,
        focusable: false,
        selectedcustomer: [],
        transporte: '',
        transporteRules: [
                    v => !!v || 'Transporte is required',
                    
                ],
        conductor: '', 
        conductorRules: [
                    v => !!v || 'Conductor is required',
                    
                ],
        patente: '', 
        patenteRules: [
                    v => !!v || 'Patente is required',
                    
                ],
    }),

    methods:{
        uppercase(e){
            return e.toUpperCase();
        }
    },
    
    computed:
        mapState(['count', 'articulos', 'customer']),

  }

  
</script>
