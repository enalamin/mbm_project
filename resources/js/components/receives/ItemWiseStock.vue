<template>
    <div class="container">
        <h4 class="text-center">Receive List</h4><br/>
        <button type="button" v-if="currentRole === 'executive'"  class="btn btn-info" @click="this.$router.push('/stock-receive/add')">Receive items</button>
        <br />
        <br />

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Item ID</th>
                <th>Name</th>
                <th>Available Quantity</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in avilableStocks" :key="row.item_id">
               
                <td>{{ row.item_id }}</td>
                <td>{{ row.item_name }}</td>
                <td>{{ row.current_stock }}</td>
            </tr>
            </tbody>
        </table>
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            avilableStocks: [],
            currentRole: ''
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.currentRole = window.Laravel.user.role
        }else{
            this.currentRole = ''
        }
        debugger
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/stock-receive/avilable-stock')
                .then(response => {
                    this.avilableStocks = response.data;
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
