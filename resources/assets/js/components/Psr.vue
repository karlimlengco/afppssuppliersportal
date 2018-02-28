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
                    <thead>
                        <tr>
                            <th>PCCO</th>
                            <th>PROJECT</th>
                            <th>ABC</th>
                            <th>UPR DATE</th>
                            <th v-if="types != 'bidding'" >ISPQ</th>
                            <th v-if="types != 'bidding'" >RFQ</th>
                            <th v-if="types != 'bidding'" >CANVASSING</th>
                            <th v-if="types == 'bidding'">Document Acceptance (BAC)</th>
                            <th v-if="types == 'bidding'">Pre Proc (BAC)</th>
                            <th v-if="types == 'bidding'">Invitation to BId (BAC)</th>
                            <th v-if="types == 'bidding'">PhilGeps Posting (BAC)</th>
                            <th v-if="types == 'bidding'">Pre Bid Conference (BAC)</th>
                            <th v-if="types == 'bidding'">SOBE (BAC)</th>
                            <th v-if="types == 'bidding'">POST QUAL (BAC)</th>
                            <th>PREPARE NOA</th>
                            <th>APPROVED NOA</th>
                            <th>RECEIVED NOA</th>
                            <th>PO/JO/WO CREATION</th>
                            <th>FUNDING</th>
                            <th>MFO FUNDING/OBLIGATION</th>
                            <th>PO COA APPROVAL</th>
                            <th>PREPARE NTP</th>
                            <th>RECEIVED NTP</th>
                            <th>RECEIVED DELIVERY</th>
                            <th>COMPLETE COA DELIVERY</th>
                            <th>TIAC</th>
                            <th>IAAR</th>
                            <th>DIIR START</th>
                            <th>DIIR CLOSE</th>
                            <th>PREPARE VOUCHER</th>
                            <th>COMPLETED</th>
                            <th>LDAP-ADA</th>
                            <th>Total Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="unit in units">
                          <tr>
                              <td>
                                  {{unit.short_code}} ({{unit.upr_count}})
                                  <button @click="fetchUnitItems(unit)" class="show-child-table"><i class="nc-icon-mini ui-1_circle-add"></i></button>
                              </td>
                              <td>--</td>
                              <td>{{formatPrice(unit.total_abc)}}</td>
                              <td>--</td>
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
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                              <td>--</td>
                          </tr>
                          <tr>
                            <td class="has-child" colspan="31">
                                <table class="child-table table-name">
                                    <tbody>
                                    <template v-for='item in items'>
                                      <template v-if='item.unit == unit.short_code'>
                                        <template v-for="itemData in item.data">
                                        <tr>
                                            <td>{{itemData.upr_number}}</td>
                                            <td>{{itemData.project_name}}</td>
                                            <td>{{formatPrice(itemData.total_amount)}}</td>
                                            <td>{{formatDate(itemData.date_prepared)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.ispq_transaction_date, itemData.date_prepared)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.rfq_created_at, itemData.date_prepared)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.canvass_start_date, itemData.ispq_transaction_date)}}</td>
                                            <td v-if="types != 'bidding'" >{{getDiff(itemData.noa_award_date, itemData.canvass_start_date)}}</td>
                                            <td v-if="types == 'bidding'">{{getDiff(itemData.doc_date, itemData.date_prepared)}}</td>
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
                                            <td>{{getDiff(itemData.vou_release, itemData.preaudit_date)}}</td>
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