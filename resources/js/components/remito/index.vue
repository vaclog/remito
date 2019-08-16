<template>
    <div>

         <b-table striped hover :items="remitos" :fields="fields"></b-table>
        
        <ul>
            <li v-for="remito in remitos" :key="remito.id">{{remito.numero_remito}}</li>
        </ul>
    </div>
</template>
<script>
export default {
    name: 'remito-index',
    data() {

        return {
            fields: [
                    {
                        key: 'id',
                        sortable: true
                    },
                    {
                        key: 'sucursal',
                        sortable: false
                    },
                    {
                        key: 'numero_remito',
                        label: 'Remito Nº',
                        sortable: true,
                        // Variant applies to the whole column, including the header and footer
                        variant: 'danger'
                    }
                ],
            remitos: [
                {
                id: 1,
                sucursal: 4,
                numero_remito: 1,
                customer_id: 1,
                transporte: 'TRansporte',
                conductor: 'Conductor',
                patente: 'ABN 0001',
                calle: 'Av. Maipu 1234',
                localidad: 'Capital Federal',
                provincia: 'Buenos Aires',

                },
                {
                id: 2,
                sucursal: 4,
                numero_remito: 2,
                customer_id: 2,
                transporte: 'TRansporte 2',
                conductor: 'Conductor 3',
                patente: 'ABN 0001',
                calle: 'Av. Maipu 1234',
                localidad: 'Capital Federal',
                provincia: 'Buenos Aires',

                },
            

            ]
        }
    }
}
</script>
