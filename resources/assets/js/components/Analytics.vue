<template>
<div class=" ">
    <h1>Procurement Status Monitoring</h1>
    <button class='button button-unfocus' v-bind:id="[ isActived  == 'alternative' ? 'button-focus' : '']" v-on:click="changeType('alternative')" >Alternative</button>
    <button class='button button-unfocus'  v-bind:id="[ isActived == 'bidding' ? 'button-focus' : '']" v-on:click="changeType('bidding')" >Bidding</button>
    <div class="table-scroll">
        <table class="table table--with-border table-name">
            <thead>
                <tr>
                    <th></th>
                    <th># UPR</th>
                    <th>Total ABC</th>
                    <th>Approved Contract Amount</th>
                    <th>Residual Amount</th>
                    <th>AVE days to complete</th>
                    <th>AVE delays</th>
                    <th style="text-align:center">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <tr>
                        <td>
                            Program {{item.programs}}
                            <button  v-on:click="clickItemProgram(item)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                        </td>
                        <td>
                            <span tooltip="Total" >{{item.upr_count}}</span>
                            <span tooltip="Completed" class="blue">({{item.completed_count}})</span>
                            <span tooltip="Ongoing" class="green">({{item.ongoing_count}})</span>
                            <span tooltip="Delay" class="red">({{item.delay_count}})</span>
                        </td>
                        <td>{{formatPrice(item.total_abc)}}</td>
                        <td>{{formatPrice(item.total_bid)}}</td>
                        <td>{{formatPrice(item.total_residual)}}</td>
                        <td>{{item.avg_days}}</td>
                        <td v-if="item.avg_delays >= 0" >{{item.avg_delays}}</td>
                        <td v-if="item.avg_delays < 0">0</td>
                        <td></td>
                    </tr>
                    <!-- child -->
                    <template v-for="itemProg in itemProgram">
                        <template v-if="itemProg.program == item.programs">
                            <tr v-for="itemProgData in itemProg.data">

                                <td class="has-child" colspan="8">
                                    <table class="child-table table-name">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{itemProgData.name}}
                                                    <button  v-on:click="clickItemProgramCenter(itemProgData)" class="show-grand-child-table" ><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                </td>
                                                <td>
                                                    <span tooltip="Total" >{{itemProgData.upr_count}}</span>
                                                    <span tooltip="Completed" class="blue">({{itemProgData.completed_count}})</span>
                                                    <span tooltip="Ongoing" class="green">({{itemProgData.ongoing_count}})</span>
                                                    <span tooltip="Delay" class="red">({{itemProgData.delay_count}})</span>
                                                </td>
                                                <td>{{formatPrice(itemProgData.total_abc)}}</td>
                                                <td>{{formatPrice(itemProgData.total_bid)}}</td>
                                                <td>{{formatPrice(itemProgData.total_residual)}}</td>
                                                <td>{{itemProgData.avg_days}}</td>

                                                <td v-if="itemProgData.avg_delays >= 0">{{itemProgData.avg_delays}}</td>
                                                <td v-if="itemProgData.avg_delays < 0">0</td>
                                                <td></td>
                                            </tr>
                                            <!-- grand child -->

                                            <template v-for="itemProgCent in itemProgramCenters">
                                                <template v-if="itemProgCent.program == item.programs">
                                                    <template v-if="itemProgCent.center == itemProgData.name">
                                                        <tr >
                                                            <td class="has-child" colspan="8">
                                                                <table class="grand-child-table table-name">
                                                                    <tbody>
                                                                        <tr  v-for="itemProgCentData in itemProgCent.data">
                                                                            <td>{{itemProgCentData.upr_number}} <small style="display:block"><a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/'+itemProgCentData.id ">({{itemProgCentData.project_name}})</a></small></td>
                                                                            <td>

                                                                                <span tooltip="Total" >{{itemProgCentData.upr_count}}</span>
                                                                                <span tooltip="Completed" class="blue">({{itemProgCentData.completed_count}})</span>
                                                                                <span tooltip="Ongoing" class="green">({{itemProgCentData.ongoing_count}})</span>
                                                                                <span tooltip="Delay" class="red">({{itemProgCentData.delay_count}})</span>
                                                                            </td>
                                                                            <td>{{formatPrice(itemProgCentData.total_abc)}}</td>
                                                                            <td>{{formatPrice(itemProgCentData.total_bid)}}</td>
                                                                            <td>{{formatPrice(itemProgCentData.total_residual)}}</td>
                                                                            <td>{{itemProgCentData.avg_days}}</td>

                                                                            <td v-if="itemProgCentData.avg_delays >= 0">{{itemProgCentData.avg_delays}}</td>
                                                                            <td v-if="itemProgCentData.avg_delays < 0">0</td>
                                                                            <td  style="text-align:left" v-if="itemProgCentData.status != 'pending'">{{itemProgCentData.status}}</td>
                                                                            <td  style="text-align:left" v-else>UPR Processing</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                    </template>
                                                </template>
                                            </template>

                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                        </template>
                    </template>

                </template>

            </tbody>
        </table>
    </div>
</div>
</template>

<script>

var arrayIDs    =   [];
var array2IDs    =   [];
    export default {
        data() {
            return{
                items: [],
                itemProgram: [],
                itemProgramCenters:[],
                types:"alternative",
            }
        },

        mounted() {
            this.fetchUprAnalytics(this.types);
        },

        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            fetchUprAnalytics: function(type) {
                axios.get('/reports/upr-programs/'+type)
                    .then(response => {
                        this.items = response.data
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUPRCenters: function(program){
                axios.get('/reports/upr-centers/'+program+'/'+this.types)
                    .then(response => {
                        this.itemProgram.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUPRs: function(program, center){
                axios.get('/reports/uprs/'+program+'/'+center+'/'+this.types)
                    .then(response => {
                        this.itemProgramCenters.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            clickItemProgram: function(item){

                // if( arrayIDs.indexOf(item.programs) == -1 )
                // {
                    arrayIDs.push(item.programs);
                    this.fetchUPRCenters(item.programs)
                // }
            },
            changeType: function(type){
                this.types = type
                this.itemProgram = []
                this.itemProgramCenters = []
                this.fetchUprAnalytics(type)
            },
            clickItemProgramCenter: function(item){
                // if( array2IDs.indexOf(item.programs) == -1 )
                // {
                    // if(array2IDs[item.programs] != item.name)
                    // {
                        console.log('asd')
                        array2IDs[item.programs]    =   item.name;
                        this.fetchUPRs(item.programs, item.name)
                    // }
                // }
            }
        },
        computed: {
            isActived: function () {
                // if(this.types = 'alternative'){
                    // return true;
                // }
                // return false;
                return this.types
            }
        }
    }
</script>
