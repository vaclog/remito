<template>
  <div>
      <h1>Clients</h1>
        <div class="row">
          <div class="col-md-10"></div>
          <div class="col-md-2">
            <router-link :to="{ name: 'create' }" class="btn btn-primary">Create Client</router-link>
          </div>
        </div><br />

        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Razon Social</th>
                <th>Disabled</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="client in clients" :key="client.id">
                    <td>{{ client.id }}</td>
                    <td>{{ client.razon_social }}</td>
                    <td>{{ client.disabled }}</td>
                    <td><router-link :to="{name: 'edit', params: { id: client.id }}" class="btn btn-primary">Edit</router-link></td>
                    <td><button class="btn btn-danger">Delete</button></td>
                </tr>
            </tbody>
        </table>
  </div>
</template>

<script>
  export default {
      data() {
        return {
          clients: []
        }
      },
      created() {
      let uri = '/api/clients';
      axios.get(uri).then(response => {
        this.clients = response.data.data;
      });
    }
  }
</script>