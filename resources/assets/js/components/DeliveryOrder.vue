<template>
<div>
    <div class="row">
        <div class="six columns">
            <h1>Delivery Order Monitoring</h1>
        </div>
    </div>
    <div  id="programs">
        <div class="table-scroll">
            <table class="table table--with-border table-name " id="bidding">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>DR #/ DR Date</th>
                        <th>Qty</th>
                        <th>Received</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <tr>
                            <td>
                                {{item.description}}
                                <button v-on:click="clickitemDelivery(item)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                            </td>
                            <td>{{item.dr_count}}</td>
                            <td>{{item.quantity}}</td>
                            <td>{{item.received_quantity}}</td>
                            <td>{{item.quantity - item.received_quantity}}</td>
                        </tr>
                        <!-- child -->
                            <tr>
                                <td class="has-child" colspan="5">
                                    <table class="child-table table-name">
                                        <tbody>
                                            <template v-for="itemProg in itemDelivery">
                                                <!-- {{item.description}} -->
                                                <template v-if="itemProg.item == item.description">
                                                <template v-for="itemProgData in itemProg.data">
                                                    <!-- <template v-if="itemProgData.quantity != 0 "> -->
                                                    <tr>
                                                        <td>{{itemProgData.description}}</td>
                                                        <td>{{itemProgData.delivery_number}}
                                                            <p style="margin-bottom:3px">{{itemProgData.delivery_date}}</p>
                                                        </td>
                                                        <td>{{itemProgData.quantity}}</td>
                                                        <td>{{itemProgData.received_quantity}}</td>
                                                        <td>{{itemProgData.quantity - itemProgData.received_quantity}}</td>
                                                    </tr>
                                                    <!-- </template> -->
                                                </template>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <!-- child -->

                    </template>

                </tbody>
            </table>

        </div>
    </div>

</div>
</template>

<script>

var arrayIDs            =   [];
    export default {
        data() {
            return{
                items: [],
                itemDelivery: [],
                show:true,
                endDate: "",
                startDate: ""
            }
        },

        mounted() {
            this.fetchUprAnalytics();
        },

        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            fetchUprAnalytics: function(type) {
                axios.get('/procurements/delivery-orders/get-item-lists/'+deliveryOrder+'?date_from='+this.startDate+'&&date_to='+this.endDate)
                    .then(response => {
                        this.items = response.data.data
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchItemOrder: function(itemId){
                axios.get('/procurements/delivery-orders/get-item-orders/'+deliveryOrder+'/?item='+encodeURIComponent(itemId))
                    .then(response => {
                        this.itemDelivery.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            clickitemDelivery: function(item){
                if( arrayIDs.indexOf(item.description) == -1 )
                {
                    arrayIDs.push(item.description);
                    this.fetchItemOrder(item.description)
                }
            },
            search: function()
            {
                this.fetchUprAnalytics()
            }
        },
        computed: {
            total: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.upr_count);
                },0);
            },
            total_completed: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.completed_count);
                },0);
            }
        }
    }

</script>
<!-- date range -->