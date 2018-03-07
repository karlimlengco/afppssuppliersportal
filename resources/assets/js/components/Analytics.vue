<template>
<div class=" ">
    <div class="row">
        <div class="five columns">
            <h1>Procurement Status Monitoring </h1>
        </div>

        <div class="seven columns align-right">
            <div style="display: inline-block">
                <input style="width:160px" placeholder="Start Date" v-model="startDate" type="text" format="dd-MM-yyyy" id="start" name="date_from" class="input" onfocus="(this.type='date')"  onfocusout="(this.type='text')" >
            </div>

            <div style="display: inline-block">
                <input style="width:160px" placeholder="End Date" v-model="endDate" type="text" format="yyyy-MM-dd" id="end" name="date_to" class="input" onfocus="(this.type='date')" onfocusout="(this.type='text')">
            </div>
            <button class="button" @click.prevent="search()" id="dateSearch"><span class="nc-icon-mini ui-1_zoom"></span></button>
            <!-- <button tooltip="change overview" class="button" @click.prevent="changeOverview()" id="dateSearch"><span class="nc-icon-mini arrows-1_refresh-69"></span></button> -->
        </div>
    </div>
    <div  id="programs">
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
                        <th v-if="showInfo" >Total ABC</th>
                        <th v-if="showInfo" >APPROVED CONTRACT AMOUNT</th>
                        <th v-if="showInfo" >DUE FOR OBLIGATION</th>
                        <th v-if="showInfo" >BALANCE AMOUNT OF COMPLETED PROJECTS</th>
                        <th v-if="show" >NUMBER OF DAYS DELAY</th>
                        <th style="text-align:center" v-if="show" >CURRENT STATUS</th>
                        <th style="text-align:center" v-if="show" >JUSTIFICATION</th>
                        <th style="text-align:center" v-if="show" >ACTION TAKEN</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <tr>
                            <td style="font-weight:bolder">
                                Program {{item.programs}}
                                <button v-if="item.upr_count > 0" v-on:click="clickItemProgram(item)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                            </td>
                            <td style="font-weight:bolder">
                                <span tooltip="Total" style="font-weight:bolder; color:#222222" >{{item.upr_count}}</span>
                                <a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/overview/completed/'+item.programs+'?type='+types" tooltip="Completed" class="blue">({{item.completed_count}})</a>
                                <a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/overview/ongoing/'+item.programs+'?type='+types" tooltip="Ongoing" class="green">({{item.ongoing_count}})</a>
                                <a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/overview/cancelled/'+item.programs+'?type='+types" tooltip="Cancelled" style="color:#7a7a7a" >({{item.cancelled_count}})</a>
                                <a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/overview/delay/'+item.programs+'?type='+types" tooltip="Delay" class="red">({{item.delay_count}})</a>
                            </td>
                            <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(item.total_abc)}}</td>
                            <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(item.total_bid)}}</td>
                            <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(item.total_residual)}}</td>
                            <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(item.total_complete_residual)}}</td>
                            <td style="font-weight:bolder" v-if="show"></td>
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
                                                        <td  style="font-weight:bolder" >
                                                            <button  v-on:click="clickItemProgramCenter(itemProgData)" class="show-grand-child-table" ><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                            {{itemProgData.name}}
                                                        </td>
                                                        <td style="font-weight:bolder">
                                                            <span tooltip="Total" style="font-weight:bolder; color:#222222" >{{itemProgData.upr_count}}</span>
                                                            <a
                                                                target="_blank"
                                                                v-bind:href="'/procurements/unit-purchase-requests/overview/completed/'+item.programs+'/'+itemProgData.name+'/?type='+types"
                                                                tooltip="Completed"
                                                                class="blue"
                                                            >
                                                                ({{itemProgData.completed_count}})
                                                            </a>
                                                            <a
                                                                target="_blank"
                                                                v-bind:href="'/procurements/unit-purchase-requests/overview/ongoing/'+item.programs+'/'+itemProgData.name+'?type='+types"
                                                                tooltip="Ongoing"
                                                                class="green"
                                                            >
                                                                ({{itemProgData.ongoing_count}})
                                                            </a>
                                                            <a
                                                                target="_blank"
                                                                v-bind:href="'/procurements/unit-purchase-requests/overview/cancelled/'+item.programs+'/'+itemProgData.name+'?type='+types"
                                                                tooltip="Cancelled"
                                                                style="color:#7a7a7a"
                                                            >
                                                                ({{itemProgData.cancelled_count}})
                                                            </a>
                                                            <a
                                                                target="_blank"
                                                                v-bind:href="'/procurements/unit-purchase-requests/overview/delay/'+item.programs+'/'+itemProgData.name+'?type='+types"
                                                                tooltip="Delay"
                                                                class="red"
                                                            >
                                                                ({{itemProgData.delay_count}})
                                                            </a>
                                                        </td>
                                                        <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(itemProgData.total_abc)}}</td>
                                                        <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(itemProgData.total_bid)}}</td>
                                                        <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(itemProgData.total_residual)}}</td>
                                                        <td style="font-weight:bolder" v-if="showInfo">{{formatPrice(itemProgData.total_complete_residual)}}</td>

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
                                                                                    <td style="font-weight:bolder">
                                                                                        {{itemUnitData.short_code}}

                                                                                        <button  v-on:click="clickItemUnit(itemUnitData)" class="show-great-grand-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                                                    </td>
                                                                                    <td style="font-weight:bolder">

                                                                                        <span tooltip="Total" style="font-weight:bolder; color:#222222" >{{itemUnitData.upr_count}}</span>

                                                                                        <a
                                                                                            target="_blank"
                                                                                            v-bind:href="'/procurements/unit-purchase-requests/overview/completed/'+item.programs+'/'+itemProgData.name+'/'+ itemUnitData.short_code +'?type='+types"
                                                                                            tooltip="Completed"
                                                                                            class="blue"
                                                                                                 >({{itemUnitData.completed_count}}
                                                                                         )</a>

                                                                                        <a
                                                                                            target="_blank"
                                                                                            v-bind:href="'/procurements/unit-purchase-requests/overview/ongoing/'+item.programs+'/'+itemProgData.name+'/'+ itemUnitData.short_code+'?type='+types"
                                                                                            tooltip="Ongoing"
                                                                                            class="green">
                                                                                                ({{itemUnitData.ongoing_count}})
                                                                                        </a>

                                                                                        <a
                                                                                            target="_blank"
                                                                                            v-bind:href="'/procurements/unit-purchase-requests/overview/cancelled/'+item.programs+'/'+itemProgData.name+'/'+ itemUnitData.short_code+'?type='+types"
                                                                                            tooltip="Cancelled"
                                                                                            style="color:#7a7a7a">
                                                                                                ({{itemUnitData.cancelled_count}})
                                                                                        </a>

                                                                                        <a
                                                                                            target="_blank"
                                                                                            v-bind:href="'/procurements/unit-purchase-requests/overview/delay/'+item.programs+'/'+itemProgData.name+'/'+ itemUnitData.short_code+'?type='+types"
                                                                                            tooltip="Delay"
                                                                                            class="red">
                                                                                                ({{itemUnitData.delay_count}})
                                                                                        </a>

                                                                                    </td>
                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemUnitData.total_abc)}}</td>
                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemUnitData.total_bid)}}</td>
                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemUnitData.total_residual)}}</td>
                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemUnitData.total_complete_residual)}}</td>

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
                                                                                                    <td style="font-weight:bolder"> <i class="green" style="font-family: Verdana;">{{itemProgCentData.upr_number}}</i> <small style="display:block"><a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/timelines/'+itemProgCentData.id ">({{itemProgCentData.project_name}})</a></small>

                                                                                                    <small>({{formatDate(itemProgCentData.date_processed)}})</small>
                                                                                                    </td>
                                                                                                    <td @click.prevent="changeInfo()">

<!--                                                                                                         <span tooltip="Total" style="font-weight:bolder; color:#222222" >{{itemProgCentData.upr_count}}</span> style="font-weight:bolder"
                                                                                                        <span tooltip="Completed" class="blue">({{itemProgCentData.completed_count}})</span>
                                                                                                        <span tooltip="Ongoing" class="green">({{itemProgCentData.ongoing_count}})</span>
                                                                                                        <span tooltip="Delay" class="red">({{itemProgCentData.delay_count}})</span>
 -->                                                                                                    <span class="blue" v-if="itemProgCentData.completed_count != 0 && itemProgCentData.completed_count != null">Completed</span>
                                                                                                        <span  class="red" v-if="itemProgCentData.delay_count != 0 && itemProgCentData.status != 'cancelled' ">Delayed</span>
                                                                                                        <span  v-if="itemProgCentData.status == 'cancelled' || itemProgCentData.status =='Cancelled' ">Cancelled</span>
                                                                                                        <span class="green" v-if="itemProgCentData.delay_count == 0 && itemProgCentData.ongoing_count != 0 && itemProgCentData.status != 'cancelled' ">Ongoing</span>

                                                                                                    </td>
                                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemProgCentData.total_abc)}}</td>
                                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemProgCentData.total_bid)}}</td>
                                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{formatPrice(itemProgCentData.total_residual)}}</td>
                                                                                                    <td style="font-weight:bolder" v-if="showInfo" >{{itemProgCentData.total_complete_residual}}</td>

                                                                                                    <td style="font-weight:bolder" v-if="show && itemProgCentData.status != 'completed'  ">
                                                                                                    <span v-if="itemProgCentData.status != 'cancelled' && itemProgCentData.delay_count != 0">{{itemProgCentData.delay}}</span>
                                                                                                    </td>
                                                                                                    <td v-if="itemProgCentData.status == 'completed'  "></td>
                                                                                                    <td v-if="show "  style="text-align:left">{{itemProgCentData.status}}</td>
                                                                                                    <td v-if="show"> <span v-if="itemProgCentData.delay_count != 0 && itemProgCentData.status != 'cancelled' ">{{itemProgCentData.last_remarks}}</span> </td>
                                                                                                    <td v-if="show">
                                                                                                        <span v-if="itemProgCentData.delay_count != 0 && itemProgCentData.status != 'cancelled' ">{{itemProgCentData.last_action}}</span>
                                                                                                        <a href="#" @click.prevent="viewChat(itemProgCentData)" position="left" tooltip="Unit Operation"><i class="nc-icon-mini ui-2_chat-round"></i></a>
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
                        <td style="font-weight:bolder">
                            Total
                        </td>
                        <td style="font-weight:bolder">
                            <span tooltip="Total" style="font-weight:bolder; color:#222222" >{{total}}</span>
                            <span tooltip="Completed" class="blue">({{total_completed}})</span>
                            <span tooltip="Ongoing" class="green">({{total_ongoing}})</span>
                            <span tooltip="Cancelled" style="color:#7a7a7a">({{total_cancelled}})</span>
                            <span tooltip="Delay" class="red">({{total_delay}})</span>
                        </td>
                        <td v-if="showInfo" >{{formatPrice(total_abc)}}</td>
                        <td v-if="showInfo" >{{formatPrice(total_bid)}}</td>
                        <td v-if="showInfo" >{{formatPrice(total_residual)}}</td>
                        <td v-if="showInfo" >{{formatPrice(total_residual2)}}</td>
                        <td v-if="show" ></td>
                        <td v-if="show" ></td>
                        <td v-if="show" ></td>
                        <td v-if="show" ></td>
                    </tr>

                </tbody>
            </table>


        </div>
    </div>

    <div id="psr"  style="display:none" >
        <button class='button button-unfocus'  v-bind:id="[ isPSRActived == 'psr-bidding' ? 'button-focus' : '']" v-on:click="changePSRType('psr-bidding')" >Bidding</button>

        <button class='button button-unfocus alternative' v-bind:id="[ isPSRActived  == 'psr-alternative' ? 'button-focus' : '']" v-on:click="changePSRType('psr-alternative')" >Alternative</button>
        <div class="table-scroll" >
            <!-- PSR -->
            <table  class="table table--with-border table-name" v-bind:id="psrTypes">
                <thead>
                    <tr>
                        <th style="text-align:center">UNITS</th>
                        <th># UPR</th>
                        <th v-if="show2" >Document Acceptance</th>
                        <th v-if="show2" >Pre Proc</th>
                        <th v-if="show2" >ITB</th>
                        <th v-if="show2" >PhilGeps</th>
                        <th v-if="show2" >PRE Bid</th>
                        <th v-if="show2" >SOBE</th>
                        <th v-if="show2" >Post Qual</th>
                        <th v-if="show3" >RFQ</th>
                        <th v-if="show3" >PhilGeps</th>
                        <th v-if="show3" >ISPQ</th>
                        <th v-if="show3" >Canvass</th>
                        <th>NOA</th>
                        <th>PO</th>
                        <th>NTP</th>
                        <th>Delivery</th>
                        <th>TIAC</th>
                        <th>DIIR</th>
                        <th>Voucher</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(tItem, index) in timelineItem">
                        <tr>
                            <td style="font-weight:bolder">
                                Program {{tItem.programs}}
                                <button v-if="tItem.upr > 0" v-on:click="clickTimelineItemProgram(tItem)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                            </td>
                            <td style="font-weight:bolder">{{tItem.upr}}</td>
                            <td v-if="show2" >{{tItem.doc}}</td>
                            <td v-if="show2" >{{tItem.preproc}}</td>
                            <td v-if="show2" >{{tItem.itb}}</td>
                            <td v-if="show2" >{{tItem.philgeps}}</td>
                            <td v-if="show2" >{{tItem.prebid}}</td>
                            <td v-if="show2" >{{tItem.bidop}}</td>
                            <td v-if="show2" >{{tItem.pq}}</td>
                            <td v-if="show3" >{{tItem.rfq}}</td>
                            <td v-if="show3" >{{tItem.philgeps}}</td>
                            <td v-if="show3" >{{tItem.ispq}}</td>
                            <td v-if="show3" >{{tItem.canvass}}</td>
                            <td style="font-weight:bolder">{{tItem.noa}}</td>
                            <td style="font-weight:bolder">{{tItem.po}}</td>
                            <td style="font-weight:bolder">{{tItem.ntp}}</td>
                            <td style="font-weight:bolder">{{tItem.do}}</td>
                            <td style="font-weight:bolder">{{tItem.tiac}}</td>
                            <td style="font-weight:bolder">{{tItem.diir}}</td>
                            <td style="font-weight:bolder">{{tItem.voucher}}</td>
                        </tr>
                        <tr>
                            <td class="has-child" colspan="18">
                                <table class="child-table table-name">
                                    <tbody>
                                        <template v-for="tItemProg in timelineItemProgram">
                                        <template v-if="tItemProg.program == tItem.programs">
                                        <template  v-for="tItemProgData in tItemProg.data">

                                            <tr>
                                                <td >
                                                    <button  v-on:click="clickTimelineItemProgramCenter(tItemProgData)" class="show-grand-child-table" ><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                    {{tItemProgData.name}}
                                                </td>
                                                <td style="font-weight:bolder">{{tItemProgData.upr}}</td>
                                                <td v-if="show2">{{tItemProgData.doc}}</td>
                                                <td v-if="show2">{{tItemProgData.preproc}}</td>
                                                <td v-if="show2">{{tItemProgData.itb}}</td>
                                                <td v-if="show2">{{tItemProgData.philgeps}}</td>
                                                <td v-if="show2">{{tItemProgData.prebid}}</td>
                                                <td v-if="show2">{{tItemProgData.bidop}}</td>
                                                <td v-if="show2">{{tItemProgData.pq}}</td>
                                                <td v-if="show3" >{{tItemProgData.rfq}}</td>
                                                <td v-if="show3" >{{tItemProgData.philgeps}}</td>
                                                <td v-if="show3" >{{tItemProgData.ispq}}</td>
                                                <td v-if="show3" >{{tItemProgData.canvass}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.noa}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.po}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.ntp}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.do}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.tiac}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.diir}}</td>
                                                <td style="font-weight:bolder">{{tItemProgData.voucher}}</td>
                                            </tr>
                                            <tr >
                                                <td class="has-child" colspan="18">
                                                    <table class="grand-child-table table-name">
                                                        <tbody>
                                                            <template v-for="tItemUnit in timelineitemUnits">
                                                            <template v-if="tItemUnit.program == tItem.programs">
                                                            <template v-if="tItemUnit.center == tItemProgData.name">
                                                            <template v-for="tItemUnitData in tItemUnit.data">
                                                            <tr  >
                                                                <td style="font-weight:bolder">
                                                                    {{tItemUnitData.short_code}}

                                                                    <button  v-on:click="clickTimelineItemUnit(tItemUnitData)" class="show-great-grand-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                                                                </td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.upr}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.doc}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.preproc}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.itb}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.philgeps}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.prebid}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.bidop}}</td>
                                                                <td v-if="show2" >{{tItemUnitData.pq}}</td>
                                                                <td v-if="show3" >{{tItemUnitData.rfq}}</td>
                                                                <td v-if="show3" >{{tItemUnitData.philgeps}}</td>
                                                                <td v-if="show3" >{{tItemUnitData.ispq}}</td>
                                                                <td v-if="show3" >{{tItemUnitData.canvass}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.noa}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.po}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.ntp}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.do}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.tiac}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.diir}}</td>
                                                                <td style="font-weight:bolder">{{tItemUnitData.voucher}}</td>
                                                            </tr>
                                                            <!-- Great Grand -->
                                                            <tr>
                                                                <td class="has-child" colspan="18">
                                                                    <table class="great-grand-child-table table-name">
                                                                        <tbody>
                                                                            <template v-for="tItemProgCent in timelineitemProgramCenters">
                                                                            <template v-if="tItemProgCent.program == tItemUnitData.short_code">
                                                                            <template v-if="tItemProgCent.center == tItemProgData.name">
                                                                            <tr  v-for="tItemProgCentData in tItemProgCent.data">
                                                                                <td style="font-weight:bolder"> <i class="green" style="font-family: Verdana;">{{tItemProgCentData.upr_number}}</i> <small style="display:block"><a target="_blank" v-bind:href="'/procurements/unit-purchase-requests/timelines/'+tItemProgCentData.id ">({{tItemProgCentData.project_name}})</a></small></td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.upr}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.doc}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.preproc}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.itb}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.philgeps}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.prebid}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.bidop}}</td>
                                                                                <td v-if="show2" >{{tItemProgCentData.pq}}</td>
                                                                                <td v-if="show3" >{{tItemProgCentData.rfq}}</td>
                                                                                <td v-if="show3" >{{tItemProgCentData.philgeps}}</td>
                                                                                <td v-if="show3" >{{tItemProgCentData.ispq}}</td>
                                                                                <td v-if="show3" >{{tItemProgCentData.canvass}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.noa}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.po}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.ntp}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.do}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.tiac}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.diir}}</td>
                                                                                <td style="font-weight:bolder">{{tItemProgCentData.voucher}}</td>
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
                            </td>
                        </tr>

                    </template>
                </tbody>
            </table>
        </div>
    </div>

</div>
</template>

<script>

var arrayIDs            =   [];
var arrayProgramCenter  =   [];
var array2IDs           =   [];

var tarrayIDs            =   [];
var tarrayProgramCenter  =   [];
var tarray2IDs           =   [];
    export default {
        data() {
            return{
                isLoading: false,
                items: [],
                timelineItem: [],
                timelineItemProgram: [],
                timelineitemUnits: [],
                timelineitemProgramCenters:[],
                itemProgram: [],
                itemProgramCenters:[],
                itemUnits:[],
                types:"bidding",
                psrTypes:"psr-bidding",
                show:false,
                showInfo:true,
                show2:true,
                show3:false,
                endDate: "",
                startDate: ""
            }
        },

        mounted() {
            this.fetchUprAnalytics(this.types);
            this.fetchTimeline(this.psrTypes);
        },

        methods: {
            viewChat(item){
                $('.chat').addClass('is-visible');
                $('.inbox').addClass('is-visible');
                $('#chatHead').html(item.upr_number);
                axios.get('/api/upr-message/'+item.id)
                    .then(response => {
                        this.$parent.$emit('getmessage', {
                            message: {
                                sender_id: currentUser.id,
                                id: response.data,
                                upr_id : item.id
                        }
                    });
                });

            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            formatDate(value) {
                if(value){
                  return moment(value).format('MMM DD YYYY')
                }
            },
            fetchUprAnalytics: function(type) {
                axios.get('/reports/programs/'+type+'?date_from='+this.startDate+'&&date_to='+this.endDate)
                    .then(response => {
                        this.items = response.data
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUPRCenters: function(program){
                axios.get('/reports/upr-centers/'+program+'/'+this.types+'?date_from='+this.startDate+'&&date_to='+this.endDate)
                    .then(response => {
                        this.itemProgram.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUnits: function(program,center){
                axios.get('/reports/units/'+program+'/'+center+'/'+this.types+'?date_from='+this.startDate+'&&date_to='+this.endDate)
                    .then(response => {
                        this.itemUnits.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchUPRs: function(program, center){
                axios.get('/reports/uprs/'+program+'/'+center+'/'+this.types+'?date_from='+this.startDate+'&&date_to='+this.endDate)
                    .then(response => {
                        this.itemProgramCenters.push(response.data)
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
            changeInfo: function(item){
                if(this.showInfo == false){
                    this.showInfo = true;
                }else{
                    this.showInfo = false;
                }
            },
            clickItemUnit: function(item){
                // if(this.show == false){
                    this.show = true;
                // }else{
                //     this.show = false;
                // }


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
            },
            changeType: function(type){
                this.types = type
                this.itemProgram = []
                this.itemProgramCenters = []
                this.itemUnits = []
                arrayIDs = []
                arrayProgramCenter = []
                array2IDs = []
                $('i').removeClass('ui-1_circle-delete');
                $('.table-name').removeClass('is-visible');
                $('i').addClass('ui-1_circle-add');
                this.show = false;
                this.showInfo = true;
                this.fetchUprAnalytics(type)
            },
            fetchTimeline: function(type) {
                axios.get('/reports/program/timeline/'+type+'?date_from='+this.startDate+'&&date_to='+this.endDate)
                    .then(response => {
                        this.timelineItem = response.data
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchTimelineUPRCenters: function(program){
                axios.get('/reports/upr-centers/timeline/'+program+'/'+this.psrTypes)
                    .then(response => {
                        this.timelineItemProgram.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchTimelineUnits: function(program,center){
                axios.get('/reports/units/timeline/'+program+'/'+center+'/'+this.psrTypes)
                    .then(response => {
                        this.timelineitemUnits.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            fetchPSRUPRs: function(program, center){
                axios.get('/reports/uprs/timeline/'+program+'/'+center+'/'+this.psrTypes)
                    .then(response => {
                        this.timelineitemProgramCenters.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
            clickTimelineItemProgram: function(item){
                if( tarrayIDs.indexOf(item.programs) == -1 )
                {
                    tarrayIDs.push(item.programs);
                    this.fetchTimelineUPRCenters(item.programs)
                }
            },
            clickTimelineItemProgramCenter: function(item){
                if( tarrayProgramCenter.indexOf(item.name) == -1 )
                {
                    if(tarrayProgramCenter[item.name] != item.name)
                    {
                        tarrayProgramCenter[item.name]    =   item.name;
                        this.fetchTimelineUnits(item.programs, item.name)
                    }
                }
            },
            clickTimelineItemUnit: function(item){
                if( tarrayProgramCenter.indexOf(item.name) == -1 && tarrayProgramCenter[item.name] == item.name)
                {
                    if( tarray2IDs.indexOf(item.short_code) == -1 )
                    {
                        if(tarray2IDs[item.name] != item.short_code)
                        {
                            tarray2IDs[item.name]    =   item.short_code;
                            this.fetchPSRUPRs(item.short_code, item.name)
                        }
                    }
                }
            },
            changePSRType: function(type){
                this.psrTypes = type
                this.timelineItemProgram = []
                this.timelineitemProgramCenters = []
                this.timelineitemUnits = []
                tarrayIDs = []
                tarrayProgramCenter = []
                tarray2IDs = []

                $('i').removeClass('ui-1_circle-delete');
                $('.table-name').removeClass('is-visible');
                $('i').addClass('ui-1_circle-add');
                this.show = false;
                this.fetchTimeline(type)

                if(type == 'psr-bidding')
                {
                    this.show2 = true;
                    this.show3 = false;
                }
                else
                {
                    this.show3 = true;
                    this.show2 = false;
                }
            },
            search: function()
            {
                arrayIDs = []
                arrayProgramCenter = []
                array2IDs = []

                this. itemProgram =  [],
                this. itemProgramCenters = [],
                this. itemUnits = [],

                this.fetchUPRCenters(this.types)
                this.fetchUprAnalytics(this.types)
            },
            changeOverview: function()
            {
                $("#programs").toggle();
                $("#psr").toggle();
            }
        },
        computed: {
            isActived: function () {
                return this.types
            },
            isPSRActived: function () {
                return this.psrTypes
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
            total_cancelled: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.cancelled_count);
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
            },
            total_residual2: function(){
                if(!this.items){
                    return 0;
                }
                return this.items.reduce(function (total, value){
                    return total +  Number(value.total_complete_residual);
                },0);
            }
        }
    }

</script>
<!-- date range -->