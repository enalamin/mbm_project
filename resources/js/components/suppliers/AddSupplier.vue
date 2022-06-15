<template>
    <div>
        <h4 class="text-center">Add Suplier</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addSupplier">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="supplier.name">
                    </div><br>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" v-model="supplier.address">
                    </div><br>
                    <div class="form-group">
                        <label>contact No</label>
                        <input type="text" class="form-control" v-model="supplier.contact_no">
                    </div><br>
                    <div class="form-group">
                        <label>email</label>
                        <input type="email" class="form-control" v-model="supplier.email">
                    </div><br>
                    <button type="submit" class="btn btn-primary">Add Supplier</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            supplier: {}
        }
    },
    methods: {
        addSupplier() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/suppliers/add', this.supplier)
                    .then(response => {
                        this.$router.push({name: 'suppliers'})
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>
