<template>
    <div class="container">
        <h4 class="text-center">New Stock Recieve</h4>
            <form @submit.prevent="addStockReceive">
                <div class="row">
                    <div class="col-md-3">        
                        <div class="form-group">
                            <label>Receive Number</label>
                            <input type="text" class="form-control" v-model="receive.receive_no">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" class="form-control" v-model="receive.receive_date">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Supplier</label>
                            <select class="form-control" v-model="receive.supplier_id">
                                <option value="">Select Supplier</option>
                                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{supplier.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Receive Item in stock</h3>
                        <div>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
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
                                        <input type="Number" id="price">
                                    </td>
                                    <td>
                                        <span class="btn btn-primary" style="padding:5px;" @click="addRequsitionItem()">Add to requisition</span>
                                    </td>

                                </tr>
                            </table>

                            <table id="reqItemList" style="visibility:hidden;" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Item Quantity</th>
                                        <th>Item Price</th>
                                    </tr>
                                </thead>
                                <tbody id="reqItemListBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary">Save Requistion</button>
                </form>
            
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            receive: {},
            items:[],
            suppliers:[],
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

            this.$axios.get('/api/suppliers')
                .then(response => {
                    this.suppliers = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })

        this.receive.receiveItems = [];
    },
    methods: {
        addRequsitionItem(){
            
            var itemSelector = document.getElementById('reqItem');

            var itemId = itemSelector.value;
            var itemName = itemSelector.options[itemSelector.selectedIndex].text;
            var itemQty = document.getElementById('itemQty').value;
            var itemPrice = document.getElementById('price').value;
            if(!itemId){
                alert('Select Item');
                return false;
            } else if(!itemQty && parseInt(itemQty)==0){
                alert('Enter valid Quantity');
                return false;
            } else if(!itemPrice && parseInt(itemPrice)==0){
                alert('Enter valid price');
                return false;
            } else{
                this.receive.receiveItems.push({
                    'item_id' : itemId,
                    'quantity' : itemQty,
                    'price' : itemPrice,

                })

                document.getElementById('reqItemListBody').innerHTML += `<tr>
                    <td>${itemName}</td>
                    <td>${itemQty}</td>
                    <td>${itemPrice}</td>
                </tr>`;
                

            }

            if(this.receive.receiveItems && this.receive.receiveItems.length){
                document.getElementById('reqItemList').style.visibility = "visible";
            }else{
                document.getElementById('reqItemList').style.visibility = "hidden";
            }
        },
        addStockReceive() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/stock-receive/add', this.receive)
                    .then(response => {
                        this.$router.push({name: 'stockreceive'})
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
