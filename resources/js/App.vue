<template>
    <div class="container">
        <div class="text-center main-header">
            <span class="text-secondary">Laravel SPA with Vue 3, Auth (Sanctum), MBM Group </span>
        </div>
        <div class="body-container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse">
                    <!-- for logged-in user-->
                    <div class="navbar-nav" v-if="isLoggedIn">
                        <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                        <router-link to="/items" class="nav-item nav-link" v-if="userRole === 'admin'">Items</router-link>
                        <router-link to="/suppliers" class="nav-item nav-link" v-if="userRole === 'admin'">Suppliers</router-link>
                        <router-link to="/requisitions" class="nav-item nav-link" >Requisitions</router-link>
                        <router-link to="/stock-receive" class="nav-item nav-link" v-if="userRole === 'executive'">Receive Items</router-link>
                        <router-link to="/stock-receive/avilable-stock" class="nav-item nav-link" >Items Stock</router-link>
                        <router-link to="/register" class="nav-item nav-link">Add New User</router-link>
                        <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
                    </div>

                    <!-- for non-logged user-->
                    <div class="navbar-nav" v-else>
                        <router-link to="/" class="nav-item nav-link">Home</router-link>
                        <router-link to="/login" class="nav-item nav-link">login</router-link>
                        
                        
                    </div>
                </div>
            </nav>
            
            <router-view/>
        </div>
    </div>
</template>
<style>
.btn-group > .btn, .btn-group-vertical > .btn {
    position: relative;
    flex: 1 1 auto;
    margin-right: 5px;
    border-radius: 5px !important;
}
</style>
<script>
export default {
    name: "App",
    data() {
        return {
            isLoggedIn: false,
            userRole:''
        }
    },
    created() {
        
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
            this.userRole = window.Laravel.user.role
        }else{
            this.isLoggedIn = false
            this.userRole = ''
        }
    },
    methods: {
        logout(e) {
            console.log('ss')
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        debugger
                        if (response.data.success) {
                            window.Laravel = null;
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>
