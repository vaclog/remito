<template>
    <v-app>
        <v-content class="container-fluid">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">                  
                    <div class="stepwizard-step col-xs-3"> 
                        <!-- <a href="#step-1" type="button" class="btn btn-success btn-lg">1</a> -->
                        <v-btn class="mx-2 display-1" fab dark large color="green">
                            <a href="#step-1">1</a>
                        </v-btn>
                        <p><small>Archivo Excel</small></p>
                    </div>
                    <div class="stepwizard-step col-xs-3"> 
                        <v-btn class="mx-2 display-1" fab dark large color="warning">
                            <a href="#step-2">2</a>
                        </v-btn>
                        <!-- <a href="#step-2" type="button" class="btn btn-default btn-lg" disabled="disabled">2</a> -->
                        <p><small>Cliente</small></p>
                    </div>
                    <div class="stepwizard-step col-xs-3"> 
                        <!-- <a href="#step-3" type="button" class="btn btn-default btn-lg" disabled="disabled">3</a> -->
                        <v-btn class="mx-2 display-1" fab dark large color="purple" >
                            <a href="#step-3">3</a>
                        </v-btn>
                        <p><small>Transporte</small></p>
                    </div> 
                </div>
            </div>
            <div>
                <form role="form">
                    <div class="panel panel-primary setup-content" id="step-1">
                        <div class="panel-heading">
                            <h3 class="panel-title">Archivo Excel</h3>
                        </div>
                        <div class="panel-body">
                            
                        
                                <upload-component></upload-component>
                            
                        
                        
                        </div>
                        
                    
                    </div>

                    
                    <div class="panel panel-primary setup-content" id="step-2">
                        <div class="panel-heading">
                                <h3 class="panel-title">Remito Preview</h3>
                        </div>
                        <div class="panel-body">
                            <select-client></select-client>
                            
                        </div>
                        
                    </div>
                    
                    <div class="panel panel-primary setup-content" id="step-3">
                        <div class="panel-heading">
                            <h3 class="panel-title">Transporte</h3>
                        </div>
                        <div class="panel-body">
                            
                                <transport-data></transport-data>
                        </div>
                       
                    </div>
                    
                
                </form>
            </div>
        </v-content>
    </v-app>
</template>

<script>
import { mapState } from 'vuex'
import { mapMutations } from 'vuex'
    export default {
        name: 'home',
        mounted() {
            this.setClientId(parseInt(this.$route.query.client_id));
        },
        methods: {
             ...mapMutations(
                [
                    'setClientId'
                ]
            ),

        }
        ,
        computed:
            mapState(['count', 'articulos', 'fecha_remito', 'numero_remito', 'idcliente']),
    }
</script>
