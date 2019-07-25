<template>
    <form enctype="multipart/form-data" @submit.prevent="submit">
     
     <div class="form-group">
        <label for="name">Archivo:</label>
        
         <input  class="form-control" id="archivo" 
                name="archivo" type="file"
                v-on:change="onFileChange"
                />

        
    </div>
    
     <input class="btn btn-primary" type="submit"  value="Upload">
    </form>
</template>

<script>
    export default {
        data(){
            return {
                
                archivo: {},
                errors: { }
            }
        },
        methods: {
            onFileChange(e){
                console.log(e.target.files[0]);
                this.archivo = e.target.files[0];

            },
            submit(e){
                let formData = new FormData();
                formData.append('archivo', this.archivo);

                axios.post('/upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                            }
      });
                    ;
            }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
