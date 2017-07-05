<template>
<div class=" ">

    <div class="table-scroll">
        <table class="table table--with-border table-name">
            <thead>
                <tr>
                    <th></th>
                    <th># UPR</th>
                    <th>ABC</th>
                    <th>Approved Contract Amount</th>
                    <th>Residual Amount</th>
                    <th>AVE days to complete</th>
                    <th>AVE delays</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <tr>
                        <td>
                            Program {{item.programs}}
                            <button  v-on:click="clickItemProgram(item)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                        </td>
                        <td>{{item.upr_count}} ({{item.completed_count}})</td>
                        <td>{{item.total_abc}}</td>
                        <td>{{item.total_bid}}</td>
                        <td>{{item.total_residual}}</td>
                        <td>{{item.avg_days}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- child -->
                    <tr v-for="itemProg in itemProgram">
                        <td class="has-child" colspan="8">
                            <table class="child-table table-name">
                                <tbody>
                                    <tr>
                                        <td>
                                            {{itemProg.name}}
                                            <button  v-on:click="clickItemProgramCenter(itemProg)" class="show-grand-child-table" ><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                        </td>
                                        <td>{{itemProg.upr_count}} ({{itemProg.completed_count}})</td>
                                        <td>{{itemProg.total_abc}}</td>
                                        <td>{{itemProg.total_bid}}</td>
                                        <td>{{itemProg.total_residual}}</td>
                                        <td>{{itemProg.avg_days}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- grand child -->
                                    <tr  v-for="itemProgCent in itemProgramCenters">
                                        <td class="has-child" colspan="8">
                                            <table class="grand-child-table table-name">
                                                <tbody>
                                                    <tr>
                                                        <td>{{itemProgCent.upr_number}}</td>
                                                        <td>{{itemProgCent.upr_count}} ({{itemProgCent.completed_count}})</td>
                                                        <td>{{itemProgCent.total_abc}}</td>
                                                        <td>{{itemProgCent.total_bid}}</td>
                                                        <td>{{itemProgCent.total_residual}}</td>
                                                        <td>{{itemProgCent.avg_days}}</td>
                                                        <td>NA2</td>
                                                        <td>NA2</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </td>
                    </tr>

                </template>

            </tbody>
        </table>
    </div>
</div>
</template>

<script>
    export default {
        data() {
            return{
                items: [],
                itemProgram: [],
                itemProgramCenters:[]
            }
        },

        mounted() {
            console.log('Component mounted.');
            this.fetchUprAnalytics();
        },

        methods: {
            fetchUprAnalytics: function() {
                axios.get('/reports/upr-programs')
                    .then(response => {
                        this.items = response.data
                    })
                    .catch(e => {
                        console.log(e)
                      // this.errors.push(e)
                    })
            },
            fetchUPRCenters: function($program){
                axios.get('/reports/upr-centers/'+$program)
                    .then(response => {
                        this.itemProgram = response.data
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUPRs: function($program, $center){
                axios.get('/reports/uprs/'+$program+'/'+$center)
                    .then(response => {
                        this.itemProgramCenters = response.data
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            clickItemProgram: function(item){
                this.fetchUPRCenters(item.programs)
            },
            clickItemProgramCenter: function(item){
                this.fetchUPRs(item.programs, item.name)
            }
        }
    }
</script>
