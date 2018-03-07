<template>
<div class=" ">

    <div  id="programs">

        <div class="row">
            <div class="six columns align-left">
                <button :id="[ isActived == 'bidding' ? 'button-focus' : '']" @click="changeType('bidding')" class='button button-tab' id='bidding'>Bidding</button>
                <button :id="[ isActived  == 'alternative' ? 'button-focus' : '']" @click="changeType('alternative')" class='button button-tab button-unfocus' id='alternative'>Alternative</button>
            </div>
            <div class="six columns align-right">
                <div style="display: inline-block">
                    <input type="text" id="start" name="date_from" class="input" placeholder="Start Date">
                </div>

                <div style="display: inline-block">
                    <input type="text" id="end" name="date_to" class="input" placeholder="End Date">
                </div>
                <button class="button"  @click="searchMe" ><span class="nc-icon-mini ui-1_zoom"></span></button>
                <a href="#" @click="printMe" id="printme" class="button" style="margin-bottom:10px"><span class="nc-icon-mini arrows-e_archive-e-download"></span></a>
            </div>
        </div>


        <div class="row">
          <div class="twelve columns">
            <div class="table-scroll">
                <table class="table table--with-border table-name">
                    <thead style="background-color:#222222">
                        <tr>
                            <th style="background:#2222">PC/CO (UPR NUMBER)</th>
                            <th style="background:#2222">PROJECT NAME/ ACTIVITY</th>
                            <th style="background:#2222">END USER</th>
                            <th style="background:#2222">ABC</th>
                            <th style="background:#2222">
                              UPR RECEIPT
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types != 'bidding'">
                              ITSPQ
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222" v-if="types != 'bidding'">
                              PHILGEPS POSTING
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222" v-if="types != 'bidding'">
                              RFQ
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222" v-if="types != 'bidding'">
                              CANVASS
                              <br>
                              <small>(PC/CO)</small>
                            </th>


                            <th style="background:#2222" v-if="types == 'bidding'">
                              Document Acceptance (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types == 'bidding'">
                              Pre Proc (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types == 'bidding'">
                              Invitation to BId (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types == 'bidding'">
                              PhilGeps Posting (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types == 'bidding'">
                              Pre Bid Conference (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types == 'bidding'">
                              SOBE (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>
                            <th style="background:#2222" v-if="types == 'bidding'">
                              POST QUAL (BAC)
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              PREPARE NOA
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              ISSUE NOA
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              CONFORME NOA
                              <br>
                              <small>(SUPPLIER)</small>
                            </th>

                            <th style="background:#2222">
                              POSTING OF NOA TO PHILGEPS
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              PREPARATION OF PO
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              MFO OBLIGATION
                              <br>
                              <small>(MFO/BUDGET OFFICE)</small>
                            </th>

                            <th style="background:#2222">
                              ISSUANCE OF CAF
                              <br>
                              <small>(ACCOUNTING)</small>
                            </th>

                            <th style="background:#2222">
                              PO/WO/JO/CA APPROVAL AND
                              NTP PREPARATION
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              SERVING OF NTP
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              CONFORME OF NTP
                              <br>
                              <small>(SUPPLIER)</small>
                            </th>

                            <th style="background:#2222">
                              PHILGEPS POSTING OF NTP
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              ISSUANCE OF NOTICE OF DELIVERY
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              DELIVERY OF ITEMS
                              <br>
                              <small>(SUPPLIER)</small>
                            </th>

                            <th style="background:#2222">
                              NOTIFICATION OF DELIVERY TO COA
                              <br>
                              <small>(SAO)</small>
                            </th>

                            <th style="background:#2222">
                              CONDUCT OF TIAC
                              <br>
                              <small>(END-USER)</small>
                            </th>

                            <th style="background:#2222">
                              INSPECTION AND ACCEPTANCE REPORT
                              <br>
                              <small>(SAO)</small>
                            </th>

                            <th style="background:#2222">
                              DELIVERED ITEMS AND INSPECTION REPORT
                              <br>
                              <small>(MFO)</small>
                            </th>

                            <th style="background:#2222">
                              PREPARATION OF DV
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              SIGN BOX `A` OF DV
                              <br>
                              <small>(MFO)</small>
                            </th>

                            <th style="background:#2222">
                              ACCOMPLISH BOX `B` OF DV AND SIGN BOX `C` OF DV
                              <br>
                              <small>(ACCOUNTING)</small>
                            </th>

                            <th style="background:#2222">
                              SIGN BOX `D` OF DV
                              <br>
                              <small>(END USER CMDR)</small>
                            </th>

                            <th style="background:#2222">
                              PRE-AUDIT
                              <br>
                              <small>(MFO)</small>
                            </th>

                            <th style="background:#2222">
                              PREPARE AND SIGN LDDAP-ADA
                              <br>
                              <small>(ACCOUNTING)</small>
                            </th>

                            <th style="background:#2222">
                              SIGN LDDAP-ADA OR PREPARE CHEQUE
                              <br>
                              <small>(FINANCE)</small>
                            </th>

                            <th style="background:#2222">
                              SIGN LDDAP-ADA OR COUNTER-SIGN CHEQUE
                              <br>
                              <small>(PC/CO)</small>
                            </th>

                            <th style="background:#2222">
                              RECEIPT OF CHEQUES AND ISSUE OFFICIAL RECEIPT
                              <br>
                              <small>(SUPPLIER)</small>
                            </th>

                            <th style="background:#2222">
                              TOTAL NUMBER OF DAYS
                            </th>

<!--
                            <th style="background:#2222">PC/CO</th>
                            <th style="background:#2222">PROJECT</th>
                            <th style="background:#2222">ABC</th>
                            <th style="background:#2222">UPR DATE</th>
                            <th style="background:#2222" v-if="types != 'bidding'">ISPQ</th>
                            <th style="background:#2222" v-if="types != 'bidding'">RFQ</th>
                            <th style="background:#2222" v-if="types != 'bidding'">CANVASSING</th>
                            <th style="background:#2222">PREPARE NOA</th>
                            <th style="background:#2222">APPROVED NOA</th>
                            <th style="background:#2222">RECEIVED NOA</th>
                            <th style="background:#2222">PO/JO/WO CREATION</th>
                            <th style="background:#2222">FUNDING</th>
                            <th style="background:#2222">MFO FUNDING/OBLIGATION</th>
                            <th style="background:#2222">PO COA APPROVAL</th>
                            <th style="background:#2222">PREPARE NTP</th>
                            <th style="background:#2222">RECEIVED NTP</th>
                            <th style="background:#2222">RECEIVED DELIVERY</th>
                            <th style="background:#2222">COMPLETE COA DELIVERY</th>
                            <th style="background:#2222">TIAC</th>
                            <th style="background:#2222">IAAR</th>
                            <th style="background:#2222">DIIR START</th>
                            <th style="background:#2222">DIIR CLOSE</th>
                            <th style="background:#2222">PREPARE VOUCHER</th>
                            <th style="background:#2222">COMPLETED</th>
                            <th style="background:#2222">LDAP-ADA</th>
                            <th style="background:#2222">Total Days</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="unit in units">
                          <tr>
                              <td style="font-weight:bolder">
                                  {{unit.short_code}} <span tooltip="Total" >({{unit.upr_count}})</span> <span class="blue" tooltip="Completed" >({{unit.completed_count}})</span>  <span class="green" tooltip="Ongoing" >({{unit.ongoing_count}})</span>  <span style="color:#7a7a7a" tooltip="Cancelled" >({{unit.cancelled_count}})</span><span  class="red" tooltip="Delay" >({{unit.delay_count}})</span>
                                  <button @click="fetchUnitItems(unit)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                              </td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">{{formatPrice(unit.total_abc)}}</td>
                              <td v-if="types != 'bidding'">--</td>
                              <td v-if="types != 'bidding'">--</td>
                              <td v-if="types != 'bidding'">--</td>
                              <td v-if="types != 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td v-if="types == 'bidding'">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                              <td style="font-weight:bolder">--</td>
                          </tr>
                          <tr>
                            <td class="has-child" colspan="40">
                                <table class="child-table table-name">
                                    <tbody>
                                    <template v-for='item in items'>
                                      <template v-if='item.unit == unit.short_code'>
                                        <template v-for="itemData in item.data">
                                        <tr>
                                            <td style="font-weight:bolder">{{itemData.upr_number}} </td>
                                            <td style="font-weight:bolder">{{itemData.project_name}}</td>
                                            <td style="font-weight:bolder">{{itemData.end_user}}</td>
                                            <td style="font-weight:bolder">{{formatPrice(itemData.total_amount)}}</td>
                                            <td style="font-weight:bolder">{{formatDate(itemData.date_processed)}}</td>
                                            <td v-if="types != 'bidding'" >
                                                <!-- 3 -->
                                                <span v-if="itemData.ispq_days > 3" style="color:red">
                                                   {{itemData.ispq_days}}
                                                </span>
                                                <span v-if="itemData.ispq_days <= 3" >
                                                   {{itemData.ispq_days}}
                                                </span>
                                            </td>
                                            <td v-if="types != 'bidding'" >
                                                <!-- 3 -->
                                                <span v-if="itemData.pp_days > 3" style="color:red">
                                                  {{itemData.pp_days}}
                                                  <!-- {{getDiff(itemData.rfq_created_at, itemData.date_processed)}} -->
                                                </span>
                                                <span v-if="itemData.pp_days <= 3" >
                                                  {{itemData.pp_days}}
                                                  <!-- {{getDiff(itemData.rfq_created_at, itemData.date_processed)}} -->
                                                </span>
                                            </td>
                                            <td v-if="types != 'bidding'" >
                                                <!-- 2 -->
                                              <span v-if="itemData.rfq_days > 3" style="color:red">
                                                  {{itemData.rfq_days}}
                                                <!-- {{getDiff(itemData.canvass_start_date, itemData.ispq_transaction_date)}} -->
                                              </span>
                                              <span v-if="itemData.rfq_days <= 3" >
                                                  {{itemData.rfq_days}}
                                                <!-- {{getDiff(itemData.canvass_start_date, itemData.ispq_transaction_date)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types != 'bidding'" >
                                                <!-- 2 -->
                                              <span v-if="itemData.canvass_days > 2" style="color:red">
                                                {{itemData.canvass_days}}
                                                <!-- {{getDiff(itemData.noa_award_date, itemData.canvass_start_date)}} -->
                                              </span>
                                              <span v-if="itemData.canvass_days <= 2" >
                                                {{itemData.canvass_days}}
                                                <!-- {{getDiff(itemData.noa_award_date, itemData.canvass_start_date)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types != 'bidding'" >
                                                <!-- 2 -->
                                              <span v-if="itemData.noa_days > 2" style="color:red">
                                                {{itemData.noa_days}}
                                                <!-- {{getDiff(itemData.noa_award_date, itemData.canvass_start_date)}} -->
                                              </span>
                                              <span v-if="itemData.noa_days <= 2" >
                                                {{itemData.noa_days}}
                                                <!-- {{getDiff(itemData.noa_award_date, itemData.canvass_start_date)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 1 -->
                                              <span v-if="itemData.doc_days> 1" style="color:red">
                                             {{ itemData.doc_days}}
                                                <!-- {{getDiff(itemData.doc_date, itemData.date_processed)}} -->
                                              </span>
                                              <span v-if="itemData.doc_days<= 1" >
                                             {{ itemData.doc_days}}
                                                <!-- {{getDiff(itemData.doc_date, itemData.date_processed)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 1 -->
                                              <span v-if="itemData.proc_days > 1" style="color:red">
                                               {{itemData.proc_days}}
                                                <!-- {{getDiff(itemData.proc_date, itemData.doc_date)}} -->
                                              </span>
                                              <span v-if="itemData.proc_days <= 1" >
                                               {{itemData.proc_days}}
                                                <!-- {{getDiff(itemData.proc_date, itemData.doc_date)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 7 -->
                                              <span v-if=" itemData.itb_days > 7" style="color:red">
                                                 {{itemData.itb_days}}
                                                {{getDiff(itemData.itb_date, itemData.proc_date)}}
                                              </span>
                                              <span v-if=" itemData.itb_days <= 7" >
                                                 {{itemData.itb_days}}
                                                {{getDiff(itemData.itb_date, itemData.proc_date)}}
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 1 -->
                                              <span v-if=" itemData.pp_days > 1" style="color:red">
                                                {{itemData.pp_days}}
                                                {{getDiff(itemData.pp_completed_at, itemData.itb_date)}}
                                              </span>
                                              <span v-if=" itemData.pp_days <= 1" >
                                                {{itemData.pp_days}}
                                                {{getDiff(itemData.pp_completed_at, itemData.itb_date)}}
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 45 -->
                                              <span v-if="itemData.prebid_days > 45" style="color:red">
                                                 {{itemData.prebid_days}}
                                                <!-- {{getDiff(itemData.prebid_date, itemData.pp_completed_at)}} -->
                                              </span>
                                              <span v-if="itemData.prebid_days <= 45" >
                                                 {{itemData.prebid_days}}
                                                <!-- {{getDiff(itemData.prebid_date, itemData.pp_completed_at)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 7 -->
                                              <span v-if="itemData.bid_days > 7" style="color:red">
                                               {{itemData.bid_days }}
                                                <!-- {{getDiff(itemData.prebid_date, itemData.pp_completed_at)}} -->
                                              </span>
                                              <span v-if="itemData.bid_days <= 7" >
                                               {{itemData.bid_days }}
                                                <!-- {{getDiff(itemData.prebid_date, itemData.pp_completed_at)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 45 -->
                                              <span v-if="pq_days > 45" style="color:red">
                                             {{itemData.pq_days}}
                                                <!-- {{getDiff(itemData.pq_date, itemData.bid_date)}} -->
                                              </span>
                                              <span v-if="pq_days <= 45" >
                                             {{itemData.pq_days}}
                                                <!-- {{getDiff(itemData.pq_date, itemData.bid_date)}} -->
                                              </span>
                                            </td>
                                            <td v-if="types == 'bidding'">
                                              <!-- 15 -->
                                              <span v-if="itemData.noa_days > 15" style="color:red">
                                                 {{itemData.noa_days}}
                                                <!-- {{getDiff(itemData.noa_award_date, itemData.pq_date)}} -->
                                              </span>
                                              <span v-if="itemData.noa_days <= 15" >
                                                 {{itemData.noa_days}}
                                                <!-- {{getDiff(itemData.noa_award_date, itemData.pq_date)}} -->
                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if="itemData.noa_approved_days > 1" style="color:red">
                                                {{itemData.noa_approved_days}}

                                                <!-- {{getDiff(itemData.noa_approved_date, itemData.noa_award_date)}} -->
                                              </span>
                                              <span v-if="itemData.noa_approved_days <= 1" >
                                                {{itemData.noa_approved_days}}

                                                <!-- {{getDiff(itemData.noa_approved_date, itemData.noa_award_date)}} -->
                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->

                                              <span v-if="itemData.noa_received_days > 1" style="color:red">
                                                {{itemData.noa_received_days}}

                                                <!-- {{getDiff(itemData.noa_award_accepted_date, itemData.noa_approved_date)}} -->
                                              </span>
                                              <span v-if="itemData.noa_received_days <= 1" >
                                                {{itemData.noa_received_days}}

                                                <!-- {{getDiff(itemData.noa_award_accepted_date, itemData.noa_approved_date)}} -->
                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->

                                              <!-- <span v-if="itemData.noa_pp_days > 1" style="color:red"> -->
                                                <!-- {{itemData.noa_pp_days}} -->

                                                <!-- {{getDiff(itemData.noa_award_accepted_date, itemData.noa_approved_date)}} -->
                                              <!-- </span> -->
                                              <span  >
                                                {{itemData.noa_pp_days}}

                                                <!-- {{getDiff(itemData.noa_award_accepted_date, itemData.noa_approved_date)}} -->
                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 2 -->
                                              <span v-if="itemData.po_days > 2" style="color:red">
                                              {{itemData.po_days}}
<!--                                                 {{getDiff(itemData.po_create_date, itemData.noa_award_accepted_date)}}
 -->                                              </span>
                                              <span v-if="itemData.po_days <= 2" >
                                              {{itemData.po_days}}
<!--                                                 {{getDiff(itemData.po_create_date, itemData.noa_award_accepted_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 2 -->
                                              <span v-if="itemData.po_mfo_days > 2" style="color:red">
                                                   {{itemData.po_mfo_days}}
<!--                                                 {{getDiff(itemData.funding_received_date, itemData.po_create_date)}}
 -->                                              </span>
                                              <span v-if="itemData.po_mfo_days <= 2" >
                                                   {{itemData.po_mfo_days}}
<!--                                                 {{getDiff(itemData.funding_received_date, itemData.po_create_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 2 -->
                                              <span v-if="itemData.po_funding_days > 2" style="color:red">
                                                     {{itemData.po_funding_days}}
<!--                                                 {{getDiff(itemData.mfo_received_date, itemData.funding_received_date)}}
 -->                                              </span>
                                              <span v-if="itemData.po_funding_days <= 2" >
                                                     {{itemData.po_funding_days}}
<!--                                                 {{getDiff(itemData.mfo_received_date, itemData.funding_received_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if="itemData.po_coa_days > 1" style="color:red">
                                                {{itemData.po_coa_days}}

<!--                                                 {{getDiff(itemData.coa_approved_date, itemData.mfo_received_date)}}
 -->                                              </span>
                                              <span v-if="itemData.po_coa_days <= 1" >
                                                {{itemData.po_coa_days}}

<!--                                                 {{getDiff(itemData.coa_approved_date, itemData.mfo_received_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if=" itemData.ntp_days > 1" style="color:red">
                                              {{itemData.ntp_days}}
<!--                                                 {{getDiff(itemData.ntp_date, itemData.coa_approved_date)}}
 -->                                              </span>
                                              <span v-if=" itemData.ntp_days <= 1" >
                                              {{itemData.ntp_days}}
<!--                                                 {{getDiff(itemData.ntp_date, itemData.coa_approved_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if=" itemData.ntp_accepted_days > 1" style="color:red">
                                              {{itemData.ntp_accepted_days}}
<!--                                                 {{getDiff(itemData.ntp_award_date, itemData.ntp_date)}}
 -->                                              </span>
                                              <span v-if=" itemData.ntp_accepted_days <= 1" >
                                              {{itemData.ntp_accepted_days}}
<!--                                                 {{getDiff(itemData.ntp_award_date, itemData.ntp_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <!-- <span v-if=" itemData.ntp_pp_days > 1" style="color:red"> -->
                                              <!-- {{itemData.ntp_pp_days}} -->
<!--                                                 {{getDiff(itemData.ntp_award_date, itemData.ntp_date)}}
 -->
 <!-- </span> -->
                                              <span  >
                                              {{itemData.ntp_pp_days}}
<!--                                                 {{getDiff(itemData.ntp_award_date, itemData.ntp_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 2 -->
                                              <span v-if="itemData.dr_days > 2" style="color:red">
                                              {{itemData.dr_days}}
<!--                                                 {{getDiff(itemData.dr_date, itemData.ntp_award_date)}}
 -->                                              </span>
                                              <span v-if="itemData.dr_days <= 2" >
                                              {{itemData.dr_days}}
<!--                                                 {{getDiff(itemData.dr_date, itemData.ntp_award_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 2 -->
                                              {{itemData.dr_delivery_days}}
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if="itemData.dr_dr_coa_days> 1" style="color:red">
                                              {{itemData.dr_dr_coa_days}}
<!--                                                 {{getDiff(itemData.dr_inspection, itemData.dr_coa_date)}}
 -->                                              </span>
                                              <span v-if="itemData.dr_dr_coa_days<= 1" >
                                              {{itemData.dr_dr_coa_days}}
<!--                                                 {{getDiff(itemData.dr_inspection, itemData.dr_coa_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 2 -->

                                              <span v-if="itemData.dr_inspection_days > 2" style="color:red">
                                              {{itemData.dr_inspection_days}}
<!--                                                 {{getDiff(itemData.iar_accepted_date, itemData.dr_inspection)}}
 -->                                              </span>
                                              <span v-if="itemData.dr_inspection_days <= 2" >
                                              {{itemData.dr_inspection_days}}
<!--                                                 {{getDiff(itemData.iar_accepted_date, itemData.dr_inspection)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <span v-if="itemData.dr_inspection_accept_days > 2" style="color:red">
                                              {{itemData.dr_inspection_accept_days}}
<!--                                                 {{getDiff(itemData.iar_accepted_date, itemData.dr_inspection)}}
 -->                                              </span>
                                              <span v-if="itemData.dr_inspection_accept_days <= 2" >
                                              {{itemData.dr_inspection_accept_days}}
<!--                                                 {{getDiff(itemData.iar_accepted_date, itemData.dr_inspection)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if="itemData.di_days> 1" style="color:red">
                                              {{itemData.di_days}}
<!--                                                 {{getDiff(itemData.di_close, itemData.di_start)}}
 -->                                              </span>
                                              <span v-if="itemData.di_days<= 1" >
                                              {{itemData.di_days}}
<!--                                                 {{getDiff(itemData.di_close, itemData.di_start)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if="itemData.vou_days > 1" style="color:red">
                                              {{itemData.vou_days}}
<!--                                                 {{getDiff(itemData.preaudit_date, itemData.v_transaction_date)}}
 -->                                              </span>
                                              <span v-if="itemData.vou_days <= 1" >
                                              {{itemData.vou_days}}
<!--                                                 {{getDiff(itemData.preaudit_date, itemData.v_transaction_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              <!-- 1 -->
                                              <span v-if="itemData.vou_certify_days > 1" style="color:red">
                                              {{itemData.vou_certify_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.vou_certify_days <= 1" >
                                              {{itemData.vou_certify_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 1 -->
                                              <span v-if="itemData.vou_jev_days > 1" style="color:red">
                                              {{itemData.vou_jev_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.vou_jev_days <= 1" >
                                              {{itemData.vou_jev_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 1 -->
                                              <span v-if="itemData.vou_approved_days > 1" style="color:red">
                                              {{itemData.vou_approved_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.vou_approved_days <= 1" >
                                              {{itemData.vou_approved_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 2 -->
                                              <span v-if="itemData.vou_preaudit_days > 2" style="color:red">
                                              {{itemData.vou_preaudit_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.vou_preaudit_days <= 2" >
                                              {{itemData.vou_preaudit_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 1 -->
                                              <span v-if="itemData.voucher_pc_days > 1" style="color:red">
                                              {{itemData.voucher_pc_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.voucher_pc_days <= 1" >
                                              {{itemData.voucher_pc_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 1 -->
                                              <span v-if="itemData.vou_released_days > 1" style="color:red">
                                              {{itemData.vou_released_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.vou_released_days <= 1" >
                                              {{itemData.vou_released_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 1 -->
                                              <span v-if="itemData.voucher_counter_days > 1" style="color:red">
                                              {{itemData.voucher_counter_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.voucher_counter_days <= 1" >
                                              {{itemData.voucher_counter_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">

                                              <!-- 1 -->
                                              <span v-if="itemData.voucher_received_days > 1" style="color:red">
                                              {{itemData.voucher_received_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                              <span v-if="itemData.voucher_received_days <= 1" >
                                              {{itemData.voucher_received_days}}
<!--                                                 {{getDiff(itemData.vou_release, itemData.preaudit_date)}}
 -->                                              </span>
                                            </td>
                                            <td style="font-weight:bolder">
                                              {{itemData.calendar_days}}
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
    </div>
  </div>
</div>
</template>

<script>
    export default {
        data() {
            return{
                units: [],
                itemsName: [],
                items: [],
                types:"bidding",
                endDate: "",
                startDate: ""
            }
        },
        mounted() {
          this.fetchUnitPsr(this.types);
        },
        methods:{
          formatDate: function(value){
            if(value){
               return moment(value).format('MMM DD YYYY')
            }
          },
          trimString: function(value){
            if(value){
               return value.replace('_', ' ')
            }
          },
          formatPrice(value) {
              if(value){
                let val = (value/1).toFixed(2).replace('.', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              }
          },
          getDiff: function(end, start){
            if(end != null && start != null){
              var a = moment(start, 'YYYY-MM-DD')
              var b = moment(end, 'YYYY-MM-DD')
              var result = b.diff(a, 'days')
              if(result < 0)
              {
                return 0;
              }
              return result;
            }
            return '--'
          },
          searchMe: function(){
            this.itemsName = []
            this.items = []
            var date_from       =   $('input[name=date_from]').val();
            var date_to         =   $('input[name=date_to]').val();
            var ptype = this.types
            axios.get('/reports/unit-psr/'+ptype+'?date_from='+date_from+'&&date_to='+date_to)
            .then(response => {
                this.units = response.data
            })
            .catch(e => {
                console.log(e)
            })
          },
          printMe: function(){
            var date_from       =   $('input[name=date_from]').val();
            var date_to         =   $('input[name=date_to]').val();
            var table_search    =   '';
            // table_search    =   $('input[name=table_search]').val();
            var ptype = this.types
            if (ptype =='alternative'){
              ptype = ''
            }
            window.open('/reports/transaction-psr/download/'+table_search+'?type='+ptype+'&&date_from='+date_from+'&&date_to='+date_to);
          },
          changeType: function(type){
              this.itemsName = []
              this.items = []
              this.types = type
              $('.table-name').removeClass('is-visible');
              this.fetchUnitPsr(this.types)
              this.fetchUnitItems(this.types)
          },
          fetchUnitPsr: function(type) {
            axios.get('/reports/unit-psr/'+type+'?date_from='+this.startDate+'&&date_to='+this.endDate)
            .then(response => {
                this.units = response.data
            })
            .catch(e => {
                console.log(e)
            })
          },
          fetchUnitItems: function(unit) {
            var date_from       =   $('input[name=date_from]').val();
            var date_to         =   $('input[name=date_to]').val();
            if(  this.itemsName.indexOf(unit.short_code) == -1 )
            {
                if( this.itemsName[unit.short_code] != unit.short_code)
                {
                    this.itemsName[unit.short_code]    =   unit.short_code;
                    axios.get('/reports/unit-psr-items/'+this.types+ '/' + unit.short_code +'?date_from='+date_from+'&&date_to='+date_to)
                    .then(response => {
                        this.items.push(response.data)
                    })
                    .catch(e => {
                        console.log(e)
                    })
                }
            }
            console.log(this.items)
          }
        },
        computed: {
          isActived: function () {
            return this.types
          }
        }
    }

</script>

<!-- date range -->