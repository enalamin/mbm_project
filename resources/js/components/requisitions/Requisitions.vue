<template>
    <div>
        <h4 class="text-center">Requisition List</h4><br/>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Requisition No</th>
                <th>Date</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="requisition in requisitions" :key="requisition.id">
                <td>{{ requisition.id }}</td>
                <td>{{ requisition.requisition_no }}</td>
                <td>{{ requisition.requisition_date }}</td>
                <td>{{ requisition.description }}</td>
                <td>{{ requisition.status }}</td>
                <td>{{ requisition.created_by }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link v-if="currentRole === 'employee' && requisition.status === 'draft'" :to="{name: 'editrequisition', params: { id: requisition.id }}" class="btn btn-primary">Edit
                        </router-link>
                        <span v-if="currentRole === 'admin' && requisition.status === 'draft'" @click="changeStatus(requisition.id,'approved')" class="btn btn-primary">aprove
                        </span>
                        <span v-if="currentRole === 'admin' && requisition.status === 'draft'" @click="changeStatus(requisition.id,'rejected')" class="btn btn-primary">reject
                        </span>
                        
                        <span v-if="currentRole === 'executive' && requisition.status === 'approved'" @click="issueItems()" class="btn btn-primary">Issue Items
                        </span>
                        <router-link :to="{name: 'viewrequisition', params: { id: requisition.id }}" class="btn btn-primary" target='_blank'>View
                        </router-link>
                        
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <button type="button" v-if="currentRole === 'employee'"  class="btn btn-info" @click="this.$router.push('/requisitions/add')">Create New Requisition</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            requisitions: [],
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
            this.$axios.get('/api/requisitions')
                .then(response => {
                    this.requisitions = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        changeStatus(id,status){
            
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/requisitions/status-change/${id}`,{status:status})
                    .then(response => {
                        this.$router.push({name: 'requisitions'});
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        deleteRequisition(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/requisitions/delete/${id}`)
                    .then(response => {
                        let i = this.requisitions.map(item => item.id).indexOf(id); // find index of your object
                        this.requisitions.splice(i, 1)
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
