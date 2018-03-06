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
                <button class="button" @click="searchMe"  id="dateSearch"><span class="nc-icon-mini ui-1_zoom"></span></button>
                <a href="#" @click="printMe" id="printme" class="button" style="margin-bottom:10px"><span class="nc-icon-mini arrows-e_archive-e-download"></span></a>
            </div>
        </div>


        <div class="row">
          <div class="twelve columns">
            <div class="table-scroll">
                <table class="table table--with-border table-name">
                    <thead>
                        <tr>

                            <th>PC/CO</th>
                            <th>UPR</th>

                            <th v-if="types == 'bidding'">Document Acceptance (BAC)</th>
                            <th v-if="types == 'bidding'">Pre Proc (BAC)</th>
                            <th v-if="types == 'bidding'">Invitation to BId (BAC)</th>
                            <th v-if="types == 'bidding'">PhilGeps Posting (BAC)</th>
                            <th v-if="types == 'bidding'">Pre Bid Conference (BAC)</th>
                            <th v-if="types == 'bidding'">SOBE (BAC)</th>
                            <th v-if="types == 'bidding'">POST QUAL (BAC)</th>

                            <th v-if="types != 'bidding'" >ISPQ</th>
                            <th v-if="types != 'bidding'" >PhilGeps Posting</th>
                            <th v-if="types != 'bidding'" >Close RFQ</th>
                            <th v-if="types != 'bidding'" >Canvassing</th>

                            <th>Prepare NOA</th>
                            <th>Approved NOA</th>
                            <th>Received NOA</th>
                            <th>PO/JO/WO Creation</th>
                            <th>Funding</th>
                            <th>MFO Funding/Obligation</th>
                            <th>PO COA Approval</th>
                            <th>Prepare NTP</th>
                            <th>Received NTP</th>
                            <th>Received Delivery</th>
                            <th>Complete COA Delivery</th>
                            <th>Technical Inspection</th>
                            <th>IAR Acceptance</th>
                            <th>DIIR Inspection Start</th>
                            <th>DIIR Inspection Close</th>
                            <th>Prepare Voucher</th>
                            <th>Preaudit Voucher /End</th>
                            <th>LDAP-ADA</th>

                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="unit in units">
                          <tr>
                              <td>
                                  {{unit.unit_name}}
                                  <!-- <button @click="fetchUnitItems(unit)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button> -->
                              </td>
                              <td>{{unit.upr_count}}</td>
                              <td v-if="types == 'bidding'">{{unit.doc}}</td>
                              <td v-if="types == 'bidding'">{{unit.pre_proc}}</td>
                              <td v-if="types == 'bidding'">{{unit.itb}}</td>
                              <td v-if="types == 'bidding'">{{unit.philgeps}}</td>
                              <td v-if="types == 'bidding'">{{unit.prebid}}</td>
                              <td v-if="types == 'bidding'">{{unit.bidop}}</td>
                              <td v-if="types == 'bidding'">{{unit.pq}}</td>
                              <td v-if="types != 'bidding'">{{unit.ispq}}</td>
                              <td v-if="types != 'bidding'">{{unit.philgeps}}</td>
                              <td v-if="types != 'bidding'">{{unit.rfq_close}}</td>
                              <td v-if="types != 'bidding'">{{unit.canvass}}</td>
                              <td>{{unit.noa}}</td>
                              <td>{{unit.noaa}}</td>
                              <td>{{unit.noar}}</td>
                              <td>{{unit.po}}</td>
                              <td>{{unit.po_pcco_received}}</td>
                              <td>{{unit.po_mfo_received}}</td>
                              <td>{{unit.po_coa_approved}}</td>
                              <td>{{unit.ntp}}</td>
                              <td>{{unit.ntpa}}</td>
                              <td>{{unit.delivery}}</td>
                              <td>{{unit.date_delivered_to_coa}}</td>
                              <td>{{unit.tiac}}</td>
                              <td>{{unit.coa_inspection}}</td>
                              <td>{{unit.diir_start}}</td>
                              <td>{{unit.diir_close}}</td>
                              <td>{{unit.voucher}}</td>
                              <td>{{unit.end_process}}</td>
                              <td>{{unit.ldad}}</td>
                          </tr>
                          <tr>
                            <td class="has-child" colspan="31">
                                <table class="child-table table-name">
                                    <tbody>
                                    <template v-for='item in items'>
                                      <template v-if='item.unit == unit.unit_name'>
                                        <template v-for="itemData in item.data">
                                        <tr>
                                            <td>{{itemData.upr_number}}</td>
                                            <td>{{itemData.project_name}}</td>
                                            <td>{{itemData.total_amount}}</td>
                                            <td>{{formatDate(itemData.date_processed)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.ispq_transaction_date, itemData.date_processed)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.rfq_created_at, itemData.date_processed)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.canvass_start_date, itemData.ispq_transaction_date)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.noa_award_date, itemData.canvass_start_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.doc_date, itemData.date_processed)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.proc_date, itemData.doc_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.itb_date, itemData.proc_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.pp_completed_at, itemData.itb_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.prebid_date, itemData.pp_completed_at)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.bid_date, itemData.prebid_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.pq_date, itemData.bid_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.noa_award_date, itemData.pq_date)}}</td>
                                            <td>{{getDiff(itemData.noa_approved_date, itemData.noa_award_date)}}</td>
                                            <td>{{getDiff(itemData.noa_award_accepted_date, itemData.noa_approved_date)}}</td>
                                            <td>{{getDiff(itemData.po_create_date, itemData.noa_award_accepted_date)}}</td>
                                            <td>{{getDiff(itemData.funding_received_date, itemData.po_create_date)}}</td>
                                            <td>{{getDiff(itemData.mfo_received_date, itemData.funding_received_date)}}</td>
                                            <td>{{getDiff(itemData.coa_approved_date, itemData.mfo_received_date)}}</td>
                                            <td>{{getDiff(itemData.ntp_date, itemData.coa_approved_date)}}</td>
                                            <td>{{getDiff(itemData.ntp_award_date, itemData.ntp_date)}}</td>
                                            <td>{{getDiff(itemData.dr_date, itemData.ntp_award_date)}}</td>
                                            <td>{{getDiff(itemData.dr_coa_date, itemData.dr_date)}}</td>
                                            <td>{{getDiff(itemData.dr_inspection, itemData.dr_coa_date)}}</td>
                                            <td>{{getDiff(itemData.iar_accepted_date, itemData.dr_inspection)}}</td>
                                            <td>{{getDiff(itemData.di_start, itemData.iar_accepted_date)}}</td>
                                            <td>{{getDiff(itemData.di_close, itemData.di_start)}}</td>
                                            <td>{{getDiff(itemData.v_transaction_date, itemData.di_close)}}</td>
                                            <td>{{getDiff(itemData.preaudit_date, itemData.v_transaction_date)}}</td>
                                            <td>{{itemData.calendar_days}}</td>
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
                pcco: [],
                units: [],
                itemsName: [],
                items: [],
                types:"bidding",
                endDate: "",
                startDate: ""
            }
        },
        mounted() {
          this.fetchPCCOPsr(this.types);
          // this.fetchUnitPsr(this.types);
        },
        methods:{
          formatDate: function(value){
            if(value){
               return moment(value).format('MMM DD YYYY')
            }
          },
          getDiff: function(end, start){
            if(end != null && start != null){
              var a = moment(start, 'YYYY-MM-DD')
              var b = moment(end, 'YYYY-MM-DD')
              return b.diff(a, 'days')
            }
            return '--'
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
            // window.open('/reports/transaction-psr/download/'+table_search+'?type='+ptype+'&&date_from='+date_from+'&&date_to='+date_to);
            window.open('/reports/psr/download/'+table_search+'?type='+ptype+'&&date_from='+date_from+'&&date_to='+date_to);
          },
          searchMe: function(){

            var date_from       =   $('input[name=date_from]').val();
            var date_to         =   $('input[name=date_to]').val();
            axios.get('/reports/pcco-psr?type='+this.types+'&&date_from='+date_from+'&&date_to='+date_to)
            .then(response => {
                this.units = response.data
            })
            .catch(e => {
                console.log(e)
            })
          },
          changeType: function(type){
              this.types = type
              $('.table-name').removeClass('is-visible');
              this.fetchPCCOPsr(this.types)
          },
          fetchPCCOPsr: function(type) {
            axios.get('/reports/pcco-psr?type='+type+'&&date_from='+this.startDate+'&&date_to='+this.endDate)
            .then(response => {
                this.units = response.data
            })
            .catch(e => {
                console.log(e)
            })
          },
          fetchUnitPsr: function(type) {
            axios.get('/reports/item-psr?type='+type+'date_from='+this.startDate+'&&date_to='+this.endDate)
            .then(response => {
                this.units = response.data
            })
            .catch(e => {
                console.log(e)
            })
          },
          fetchUnitItems: function(unit) {

            if(  this.itemsName.indexOf(unit.unit_name) == -1 )
            {
                if( this.itemsName[unit.unit_name] != unit.unit_name)
                {
                    this.itemsName[unit.unit_name]    =   unit.unit_name;
                    axios.get('/reports/pcco-psr-items/'+this.types+ '/' + unit.unit_name +'?date_from='+this.startDate+'&&date_to='+this.endDate)
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