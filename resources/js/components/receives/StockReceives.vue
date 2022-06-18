<template>
    <div class="container">
        <h4 class="text-center">Receive List</h4><br/>
        <button type="button" v-if="currentRole === 'executive'"  class="btn btn-info" @click="this.$router.push('/stock-receive/add')">Receive items</button>
        <br />
        <br />

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Receive No</th>
                <th>Date</th>
                <th>Supplier Name</th>
                <th>Numer of Item</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in receives" :key="row.receive_no">
               
                <td>{{ row.receive_no }}</td>
                <td>{{ row.receive_date }}</td>
                <td>{{ row.supplier_name }}</td>
                <td>{{ row.number_of_items }}</td>
                <td>
                    <div class="btn-group" role="group">
                        
                        <router-link :to="{name: 'viewstockreceive', params: { id: row.receive_no }}" class="btn btn-primary" >View
                        </router-link>
                        
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            receives: [],
            currentRole: ''
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.currentRole = window.Laravel.user.role
        }else{
            this.currentRole = ''
        }
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/stock-receive')
                .then(response => {
                    this.receives = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>
