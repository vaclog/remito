<template>
  <div>
    <h1>Create A Client</h1>
    <form @submit.prevent="addClient">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Razon Social:</label>
                    <input type="text" class="form-control" v-model="client.razon_social">
                </div>
                <div class="form-group">
                    <label>Disabled</label>
                    <input type="text" class="form-control" v-model="client.disabled">
                </div>
            </div>
        </div>
        
        <div class="form-group">
          <button class="btn btn-primary">Create</button>
        </div>
    </form>
  </div>
</template>

<script>
    export default {
        data(){
        return {
          client:{}
        }
    },
    methods: {
      addClient(){

          let uri = '/api/client/create';
            this.axios.post(uri, this.client).then((response) => {
            this.$router.push({name: 'clients'});
            });
        console.log(this.client);
      }
    }
  }
</script>