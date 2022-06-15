<template>
    <div>
        <h4 class="text-center">All Suppliers</h4><br/>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>address</th>
                <th>Contact No</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="supplier in suppliers" :key="supplier.id">
                <td>{{ supplier.id }}</td>
                <td>{{ supplier.name }}</td>
                <td>{{ supplier.address }}</td>
                <td>{{ supplier.contact_no }}</td>
                <td>{{ supplier.email }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'editsupplier', params: { id: supplier.id }}" class="btn btn-primary">Edit
                        </router-link>
                        <button class="btn btn-danger" @click="deleteSupplier(supplier.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-info" @click="this.$router.push('/suppliers/add')">Add Supplier</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            suppliers: []
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/suppliers')
                .then(response => {
                    this.suppliers = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        deleteSupplier(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/suppliers/delete/${id}`)
                    .then(response => {
                        let i = this.suppliers.map(item => item.id).indexOf(id); // find index of your object
                        this.suppliers.splice(i, 1)
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
