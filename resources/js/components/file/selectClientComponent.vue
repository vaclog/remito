<template>
    <div class="shadow p-3 mb-5 bg-white rounded">
        

        <div class="form-group">
            <label class="control-label">Company Name</label>
            
            <v-select
                v-model="select"
                :items="customers"
                item-text="nombre"
                item-value="id"
                label="Standard"
                
        ></v-select>
        </div>
        <div>
            <div class="row">
                <div class="col-8">
                    <index-item></index-item>
                </div>   
                <div class="col-4">
                    <div class="form-group">
                        
                        <v-text-field
                            v-model="cuit"
                            :rules="cuitRules"
                            label="CUIT"
                            required
                        ></v-text-field>
                            
                        
                    </div>
                    <div class="form-group">
                        <v-text-field
                            v-model="calle"
                            :rules="calleRules"
                            label="Calle"
                            required
                        ></v-text-field>
                    </div>
                    <div class="form-group">
                        <v-text-field
                            v-model="localidad"
                            :rules="localidadRules"
                            label="Localidad"
                            required
                        ></v-text-field>
                    </div>
                    <div class="form-group">
                        <v-text-field
                            v-model="provincia"
                            :rules="provinciaRules"
                            label="Provincia"
                            required
                        ></v-text-field>
                    </div>
                </div>
           </div>
        </div>
    </div>
</template>


<script>

import { mapState } from 'vuex'
import { mapMutations } from 'vuex'
import { mapGetters } from 'vuex'


export default {
    data(){
        return {
            customers: [],
            errors: { },
            select: [],
            calle: '',
            cuit: '',
            localidad: '',
            provincia: '',
            cuitRules: [
                v => !!v || 'CUIT is required',
                v => v.length <= 11 || 'Name must be less than 11 dígitos',
            ],
            calleRules: [
                v => !!v || 'Calle is required',
                
            ],
            localidadRules: [
                v => !!v || 'Localidad is required',
                
            ],
            provinciaRules: [
                v => !!v || 'Provincia is required',
                
            ],
            
        }
    },

    mounted(){
        console.log('component mounted')
        console.log(this.$store.state.customer )
        // this.selectedCustomer = this.$store.state.customer
      
        
    },
    methods:{
        ...mapMutations(
                [
                    'setCustomer'
                ]
            ),
        setActivePerson(state, customer) {
            this.selectedCustomer = customer
        },
    },
    watch:{
        cus: {
            immediate: true,
            handler(value) {
                console.log(value);
                this.select = value.id;
            }
        },
        
        select: function(){
            console.log(this.select)

           var seleccion = this.customers.find(function(e){ 
               if ( e.id == this.id) 
               { return e } 
               }, {id: this.select});

            if ( typeof(seleccion) !== 'undefined') {
                    this.cuit = seleccion.cuit
                    this.calle = seleccion.calle
                    this.localidad = seleccion.localidad
                    this.provincia = seleccion.provincia
                    this.setCustomer(seleccion)
                    
                }
            else{ 
                this.cuit = '';
                this.calle = ''
                this.localidad = ''
                this.provincia = ''
                this.setCustomer([])

            }

        }
        //     // if (this.selectedCustomer.length() > 0){
        //         //console.log(this.customers[this.selectedCustomer].nombre);
        //         console.log(this.selectedCustomer);
        //         console.log(this.selectedCustomer);
        //         if ( this.selectedCustomer) {
        //             this.cuit = this.selectedCustomer.cuit
        //             this.calle = this.selectedCustomer.calle
        //             this.localidad = this.selectedCustomer.localidad
        //             this.provincia = this.selectedCustomer.provincia
        //             this.setCustomer(this.selectedCustomer)
                    
        //         }
        //         else 
        //         {
        //             this.cuit = '';
        //             this.calle = ''
        //             this.localidad = ''
        //             this.provincia = ''
        //             this.setCustomer([])
        //         }
        //     // }
            

        // }
    },
    created() {
      let uri = '/api/customers/' + this.$route.query.client_id;
      axios.get(uri)
      .then(response => {
        this.customers = response.data;
        
        //console.log(this.$store.state.customer)
        //this.items = response.data.data;
      })
      .catch((error) => {
          console.log('errores varios')
          //console.log(error);

          this.errors = error.response.data.errors || {}

      })
    },
    computed:{
            ...mapGetters(['customerSel']),
            ...mapState(['count', 'articulos', 'customer']),
            cus() {
                return this.$store.state.customer;
      
               
            }
    }
    

    
}
</script>

