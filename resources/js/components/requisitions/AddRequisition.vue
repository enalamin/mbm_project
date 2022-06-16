<template>
    <div>
        <h4 class="text-center">Add Requisition</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addRequisition">
                    <div class="form-group">
                        <label>Requisition Number</label>
                        <input type="text" class="form-control" v-model="requisition.requisition_no">
                    </div><br>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" v-model="requisition.requisition_date">
                    </div><br>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" v-model="requisition.description">
                    </div><br>

                    <div>
                        <h3>Add Item to Requisition</h3>
                        <div>
                            <table>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>
                                        <select id="reqItem">
                                            <option value="">Select Item</option>
                                            <option v-for="item in items" :key="item.id" :value="item.id">{{item.item_name}}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="Number" id="itemQty">
                                    </td>
                                    <td>
                                        <span class="btn btn-primary" style="padding:5px;" @click="addRequsitionItem()">Add to requisition</span>
                                    </td>

                                </tr>
                            </table>

                            <table id="reqItemList" style="display:none;">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Item Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="reqItemListBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Requistion</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            requisition: {},
            items:[]
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/items')
                .then(response => {
                    this.items = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        addRequsitionItem(){
            this.requisition.requisitionItems = [];
            var itemSelector = document.getElementById('reqItem');

            var itemId = itemSelector.value;
            var itemName = itemSelector.options[itemSelector.selectedIndex].text;
            var itemQty = document.getElementById('itemQty').value;
            if(!itemId){
                alert('Select Item');
                return false;
            } else if(!itemQty && parseInt(itemQty)==0){
                alert('Enter valid Quantity');
                return false;
            }else{
                this.requisition.requisitionItems.push({
                    'item_id' : itemId,
                    'quantity' : itemQty
                })

                document.getElementById('reqItemListBody').innerHTML += `<tr>
                    <td>${itemName}</td>
                    <td>${itemQty}</td>
                </tr>`;
                

            }

            if(this.requisition.requisitionItems && this.requisition.requisitionItems.length){
                document.getElementById('reqItemList').style.display = "block";
            }else{
                document.getElementById('reqItemList').style.display = "none";
            }
        },
        addRequisition() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/requisitions/add', this.requisition)
                    .then(response => {
                        this.$router.push({name: 'requisitions'})
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
