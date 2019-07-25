<template>
    <div>
        <h1>Edit Post</h1>
        <form @submit.prevent="updateClient">
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
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          client: {}
        }
      },
      created() {
        let uri = `/api/client/edit/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
            this.client = response.data;
        });
      },
      methods: {
        updateClient() {
          let uri = `/api/client/update/${this.$route.params.id}`;
          this.axios.post(uri, this.client).then((response) => {
            this.$router.push({name: 'posts'});
          });
        }
      }
    }
</script>