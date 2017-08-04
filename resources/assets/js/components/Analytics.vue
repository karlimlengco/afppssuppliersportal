<template>
<div class=" ">
    <div class="row">
        <div class="six columns">
            <h1>Procurement Status Monitoring</h1>
        </div>
       <!--  <div class="six columns align-right" >
            <h3>UPR count Legends</h3>
            <p>
                <small style="border: 1px solid #222;background:#222; color:white; font-weight:900; padding:4px">Total</small>
                <small style="border: 1px solid rgba(41, 128, 185,1.0);background:rgba(41, 128, 185,1.0); color:white; font-weight:800; padding:4px">Completed</small>
                <small style="border: 1px solid #1d8147;background:#1d8147; color:white; font-weight:800; padding:4px">Ongoing</small>
                <small style="border: 1px solid rgba(231, 76, 60,1.0);background:rgba(231, 76, 60,1.0); color:white; font-weight:800; padding:4px">Delay</small>
            </p>
        </div> -->
    </div>
    <button class='button button-unfocus'  v-bind:id="[ isActived == 'bidding' ? 'button-focus' : '']" v-on:click="changeType('bidding')" >Bidding</button>

    <button class='button button-unfocus alternative' v-bind:id="[ isActived  == 'alternative' ? 'button-focus' : '']" v-on:click="changeType('alternative')" >Alternative</button>

    <div class="table-scroll">
        <table class="table table--with-border table-name" v-bind:id="types">
            <thead>
                <tr>
                    <th style="text-align:center">UNITS</th>
                    <th>
                        # UPR
                    </th>
                    <th>Total ABC</th>
                    <th>Approved Contract Amount</th>
                    <th>Residual Amount</th>
                    <th>AVG Days to Complete</th>
                    <th v-if="show" >Number of Days Delay</th>
                    <th style="text-align:center" v-if="show" >Current Status</th>
                    <th style="text-align:center" v-if="show" >Justification</th>
                    <th style="text-align:center" v-if="show" >Action Taken</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <tr>
                        <td>
                            Program {{item.programs}}
                            <button v-if="item.upr_count > 0" v-on:click="clickItemProgram(item)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
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
                        <td></td>
                        <td v-if="show"></td>
                        <td v-if="show"></td>
                        <td v-if="show"></td>
                        <td v-if="show"></td>
                    </tr>
                    <!-- child -->
                            <tr>
                                <td class="has-child" colspan="10">
                                    <table class="child-table table-name">
                                      <!--   <thead>
                                            <tr>
                                                <th style="text-align:center">Head</th>
                                            </tr>
                                        </thead> -->
                                        <tbody>
                                        <template v-for="itemProg in itemProgram">
                                            <template v-if="itemProg.program == item.programs">
                                            <template  v-for="itemProgData in itemProg.data">

                                                <tr>
                                                    <td >
                                                        <button  v-on:click="clickItemProgramCenter(itemProgData)" class="show-grand-child-table" ><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                        {{itemProgData.name}}
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
                                                    <td></td>

                                                    <td v-if="show" ></td>
                                                    <td v-if="show" ></td>
                                                    <td v-if="show" ></td>
                                                    <td v-if="show" ></td>
                                                </tr>
                                                <!-- Grand Child -->

                                                            <tr >
                                                                <td class="has-child" colspan="10">
                                                                    <table class="grand-child-table table-name">
                                                                        <tbody>
                                                                        <template v-for="itemUnit in itemUnits">
                                                                            <template v-if="itemUnit.program == item.programs">
                                                                                <template v-if="itemUnit.center == itemProgData.name">
                                                                        <template v-for="itemUnitData in itemUnit.data">
                                                                            <tr  >
                                                                                <td>
                                                                                    {{itemUnitData.short_code}}

                                                                                    <button  v-on:click="clickItemUnit(itemUnitData)" class="show-great-grand-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                                                </td>
                                                                                <td>

                                                                                    <span tooltip="Total" >{{itemUnitData.upr_count}}</span>
                                                                                    <span tooltip="Completed" >({{itemUnitData.completed_count}})</span>
                                                                                    <span tooltip="Ongoing" class="green">({{itemUnitData.ongoing_count}})</span>
                                                                                    <span tooltip="Delay" class="red">({{itemUnitData.delay_count}})</span>
                                                                                </td>
                                                                                <td>{{formatPrice(itemUnitData.total_abc)}}</td>
                                                                                <td>{{formatPrice(itemUnitData.total_bid)}}</td>
                                                                                <td>{{formatPrice(itemUnitData.total_residual)}}</td>
                                                                                <td></td>

                                                                                <td v-if="show" ></td>
                                                                                <td v-if="show"   style="text-align:left"></td>
                                                                                <td v-if="show" ></td>
                                                                                <td v-if="show" ></td>
                                                                            </tr>
                                                                            <!-- Great Grand -->
                                                                                    <tr>
                                                                                        <td class="has-child" colspan="10">
                                                                                            <table class="great-grand-child-table table-name">
                                                                                                <tbody>
                                                                                                <template v-for="itemProgCent in itemProgramCenters">
                                                                                                    <template v-if="itemProgCent.program == itemUnitData.short_code">
                                                                                                        <template v-if="itemProgCent.center == itemProgData.name">
                                                                                                    <tr  v-for="itemProgCentData in itemProgCent.data">
                                                                                                <td> <i class="green" style="font-family: Verdana;">{{itemProgCentData.upr_number}}</i> <small style="display:block"><a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/timelines/'+itemProgCentData.id ">({{itemProgCentData.project_name}})</a></small></td>
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

                                                                                                <td v-if="show"> {{itemProgCentData.delay}}</td>
                                                                                                <td v-if="show"  style="text-align:left">{{itemProgCentData.status}}</td>
                                                                                                <td v-if="show"> {{itemProgCentData.last_remarks}}</td>
                                                                                                <td v-if="show"> {{itemProgCentData.last_action}}</td>
                                                                                            </tr>
                                                                                                    </template>
                                                                                                </template>
                                                                                            </template>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                        </template>
                                                                            <!-- Great Grand -->

                                                                                </template>
                                                                            </template>
                                                                        </template>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                <!-- Grand Child -->

                                            </template>
                                            </template>
                                        </template>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                    <!-- child -->

                </template>
                <tr>
                    <td>
                        Total
                    </td>
                    <td>
                        <span tooltip="Total" >{{total}}</span>
                        <span tooltip="Completed" class="blue">({{total_completed}})</span>
                        <span tooltip="Ongoing" class="green">({{total_ongoing}})</span>
                        <span tooltip="Delay" class="red">({{total_delay}})</span>
                    </td>
                    <td>{{formatPrice(total_abc)}}</td>
                    <td>{{formatPrice(total_bid)}}</td>
                    <td>{{formatPrice(total_residual)}}</td>
                    <td></td>
                    <td v-if="show" ></td>
                    <td v-if="show" ></td>
                    <td v-if="show" ></td>
                    <td v-if="show" ></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
</template>

<script>

var arrayIDs            =   [];
var arrayProgramCenter  =   [];
var array2IDs           =   [];
    export default {
        data() {
            return{
                items: [],
                itemProgram: [],
                itemProgramCenters:[],
                itemUnits:[],
                types:"bidding",
                show:false
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
                axios.get('/reports/programs/'+type)
                // axios.get('/reports/upr-programs/'+type)
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
            fetchUnits: function(program,center){
                axios.get('/reports/units/'+program+'/'+center+'/'+this.types)
                    .then(response => {
                        this.itemUnits.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUPRs: function(program, center){
                axios.get('/reports/uprs/'+program+'/'+center+'/'+this.types)
                    .then(response => {
                        this.itemProgramCenters.push(response.data)
                        console.log(this.itemProgramCenters)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            clickItemProgram: function(item){
                if( arrayIDs.indexOf(item.programs) == -1 )
                {
                    arrayIDs.push(item.programs);
                    this.fetchUPRCenters(item.programs)
                }
            },
            changeType: function(type){
                this.types = type
                this.itemProgram = []
                this.itemProgramCenters = []
                this.itemUnits = []
                arrayIDs = []
                arrayProgramCenter = []
                array2IDs = []
                this.fetchUprAnalytics(type)
            },
            clickItemProgramCenter: function(item){
                if( arrayProgramCenter.indexOf(item.name) == -1 )
                {
                    if(arrayProgramCenter[item.name] != item.name)
                    {
                            arrayProgramCenter[item.name]    =   item.name;
                        this.fetchUnits(item.programs, item.name)
                    }
                }
            },
            clickItemUnit: function(item){

                // var tds = document.getElementsByTagName("td");
                // var th = document.getElementsByTagName("th");

                // for(var i = 0; i < th.length; i++) {
                //    th[i].style.display="table-cell";
                // }

                // for(var i = 0; i < tds.length; i++) {
                //    tds[i].style.display="table-cell";
                // }
                //
                if(this.show == false){
                    this.show = true;
                }else{
                    this.show = false;
                }


                if( arrayProgramCenter.indexOf(item.name) == -1 && arrayProgramCenter[item.name] == item.name)
                {
                    if( array2IDs.indexOf(item.short_code) == -1 )
                    {
                        if(array2IDs[item.name] != item.short_code)
                        {
                            array2IDs[item.name]    =   item.short_code;
                            this.fetchUPRs(item.short_code, item.name)
                        }
                    }
                }
            }
        },
        computed: {
            isActived: function () {
                return this.types
            },
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
            },
            total_ongoing: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.ongoing_count);
                },0);
            },
            total_delay: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.delay_count);
                },0);
            },
            total_abc: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.total_abc);
                },0);
            },
            total_bid: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.total_bid);
                },0);
            },
            total_residual: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.total_residual);
                },0);
            }
        }
    }
</script>
