<template>
    <div class="container">
        <h4 class="text-center">View Requisition</h4>
        <div class="row">
            <div class="col-md-6">                
                <div class="form-group">
                    <label>Requisition Number</label>
                    <input type="text" class="form-control" v-model="requisition.requisition_no" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" class="form-control" v-model="requisition.requisition_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Description</label>
                    <div>{{requisition.description}}</div>
                </div>
            </div>
        </div>
        
        
        <h3>Requisition items</h3>
        
        <div>
            <table class="table table-bordered">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Issued Quantity</th>
                </tr>
                <tr v-for="item in requisition.requisitionItems" :key="item.item_id">
                    <td>
                        {{item.item_name}}
                    </td>
                    <td>
                        {{item.amount}}
                    </td>
                    <td>
                        {{item.issued_quantity}}
                    </td>

                </tr>
            </table>
        </div>
        <div class="row" v-if="currentRole === 'executive' && requisition.status === 'approved'">
            <div class="col-md-4">
                <label>Issue Date</label>
                <input type="date" class="form-control" v-model="requisition.issue_date" >
            </div>
            <div class="col-md-8">
            <button type="button"   class="btn btn-info" @click="issueRequisition" style="margin-top:24px;">Issue Items</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            requisition: {},
            items:[],
            currentRole : ''
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.currentRole = window.Laravel.user.role
        }else{
            this.currentRole = ''
        }
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/requisitions/edit/${this.$route.params.id}`)
                .then(response => {
                    this.requisition = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        issueRequisition() {
            if(this.requisition.issue_date){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post(`/api/requisitions/issue/${this.$route.params.id}`, this.requisition)
                        .then(response => {
                            this.$router.push({name: 'requisitions'})
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            } else{
                alert('select Issue date')
            }
            
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
