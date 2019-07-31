<template>
<div class="shadow p-3 mb-5 bg-white rounded">
    <form enctype="multipart/form-data" @submit.prevent="submit">
     
     <div class="form-group">
        <label for="name">Archivo:</label>
        
            <v-file-input label="File input" @change="change" accept=".xlsx, .xls"></v-file-input>

    </div>
    <div class="form-group text-right">
    
        <!-- <input class="btn btn-secondary" type="submit"  value="Upload"> -->
        <v-spacer></v-spacer>
      <v-btn block color="primary" @click="submit" :loading="loading">Procesar</v-btn>
    </div>
    </form>
    <div>

        <index-item></index-item>
        
        
    </div>

    
    <ul v-if="errors && errors.length">

        
    <li v-for="error of errors" :key="error.id">
      {{error.message}}
    </li>
  </ul>
</div>
        
    
</template>

<script>

import { mapState } from 'vuex'
import { mapMutations } from 'vuex'
    export default {
        data(){
            return {
                loading: false,
                archivo: {},
                errors: { },
                items: {},
              
            }
        },
        methods: {
            ...mapMutations(
                [
                    'setArticulos'
                ]
            ),
            change(file){
                console.log(file)
                this.archivo = file;
                
            },
            submit(e){
                this.loading = true
                // handle login
                setTimeout(() => {
                    console.log(this)
                }, 5)
                let formData = new FormData();
                formData.append('archivo', this.archivo);

                axios.post('/upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                            // JSON responses are automatically parsed.
                            this.items = response.data
                            this.setArticulos(response.data)
                            this.loading = false;
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
                    ;
            }
        },

        mounted() {
            console.log('Upload Component mounted.')
        },
        computed:
            mapState(['count', 'articulos']),
    }
</script>
