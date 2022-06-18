<template>
    <div class="container">
        <h4 class="text-center">Receive Details</h4>
        <div class="row">
            <div class="col-md-4">                
                <div class="form-group">
                    <label>Receive Number</label>
                    <input type="text" class="form-control" v-model="receive.receive_no" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Date</label>
                    <input type="text" class="form-control" v-model="receive.receive_date" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Supplier</label>
                    <input type="text" class="form-control" v-model="receive.supplier_name" readonly>
                </div>
            </div>
        </div>
        
        
        <h3>Receive items</h3>
        
        <div>
            <table class="table table-bordered">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                <tr v-for="item in receive.receiveItems" :key="item.item_id">
                    <td>
                        {{item.item_name}}
                    </td>
                    <td>
                        {{item.quantity}}
                    </td>
                    <td>
                        {{item.price}}
                    </td>

                </tr>
            </table>
        </div>
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            receive: {},
            items:[]
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/stock-receive/edit/${this.$route.params.id}`)
                .then(response => {
                    this.receive = response.data;
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
