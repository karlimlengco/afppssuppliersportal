<template>
<div class='row'>
    <div class="modal" id="add-account-code-modal" style="z-index:999999999; left:0; top:0; right:0; bottom:0">
        <div class="modal__dialogue modal__dialogue--round-corner">

                <div class="moda__dialogue__head">
                    <h1 class="modal__title">Account Code</h1>
                </div>

                <div class="row">
                    <div class="twelve columns">
                      <selectize v-validate="'required'" id="accountCode" name="account_code" v-model="account_code" :settings="accountSettings" >
                        <option value=''>Select One</option>
                      </selectize>
                    </div>
                </div>

                <div class="modal__dialogue__body">
                    <input name="_method" type="hidden" value="POST">
                </div>

                <div class="modal__dialogue__foot">
                    <button type="button" class="button pull-left" @click.prevent="closeModal">Cancel</button>
                    <button class="button" @click.prevent="addCode">Proceed</button>
                </div>

        </div>
    </div>
    <div class="twelve columns">

    <button v-if="account_codeId != null"  type="button" @click.prevent="fileInputOpen" class="button pull-right" tooltip="Import"><i class="nc-icon-mini arrows-1_cloud-upload-96"></i></button>
    <input type="file" id="file" style="visibility:hidden" ref="myFiles" class="custom-file-input" @change="previewFiles">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th width="45%">Description</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Unit</th>
                    <th width="15%">Unit Price</th>
                    <th width="15%">Amount ({{total_amount}})</th>
                    <th width="5%"></th>
                </tr>
            </thead>
            <tbody>
            <template v-for="(codes, index) in accounts">

              <tr>
                  <tr>
                      <td class="row align-left" colspan="6" style="padding-bottom:0; margin-bottom:0">
                          <h5 class="align-left" v-model="codes.code">{{ codes.code }} <a href="#" @click.prevent="removeCode(codes, index)"><small>remove</small></a></h5>
                      </td>
                  </tr>
              </tr>
              <template v-for="(items, index) in model">
                <tr   v-if="codes.id == items.new_account_code">
                    <td class="row">
                        <p v-if="readonly">{{ items.item_description }}</p>
                        <input type="text"
                          placeholder="Item Description"
                          class="input item_description"
                          :name="'items[' + index + '][item_description]'"
                          v-validate="'required'"
                          v-if="!readonly"
                          required="required"
                          v-model="items.item_description">
                    </td>
                    <!-- <td class="row"> -->
                        <p v-if="readonly">{{ items.new_account_code }}</p>
                        <input type="text"
                          class="input"
                          style="display:none"
                          readOnly
                          :name="'items[' + index + '][new_account_code]'"
                          v-validate="'required'"
                          v-if="!readonly"
                          required
                          v-model="items.new_account_code">
                    <!-- </td> -->
                    <td class="row">
                        <p v-if="readonly">{{ items.quantity }}</p>
                        <input type="text"
                          class="input"
                          :name="'items[' + index + '][quantity]'"
                          v-validate="'required'"
                          v-if="!readonly"
                          v-model="items.quantity">
                    </td>
                    <td class="row">
                        <p v-if="readonly">{{ items.unit_measurement }}</p>
                        <input type="text"
                          class="input"
                          :name="'items[' + index + '][unit_measurement]'"
                          v-validate="'required'"
                          v-if="!readonly"
                          required
                          v-model="items.unit_measurement">
                    </td>
                    <td class="row">
                        <p v-if="readonly">{{ items.unit_price }}</p>
                        <input type="text"
                          class="input"
                          :name="'items[' + index + '][unit_price]'"
                          v-validate="'required'"
                          v-if="!readonly"
                          required
                          v-model="items.unit_price">
                    </td>
                    <td class="row">
                        <p v-if="readonly">{{ items.total_amount }}</p>
                        <input type="text"
                          class="input"
                          :name="'items[' + index + '][total_amount]'"
                          v-validate="'required'"
                          v-if="!readonly"
                          required
                          :value="total"
                          v-model="items.total_amount">
                    </td>
                          <!-- :value="total" -->
                    <td>
                        <button
                          v-if="!readonly"
                          @click.stop.prevent="deleteItem(index)"
                          class="button">
                          <i class="nc-icon-outline ui-1_simple-remove"></i>
                        </button>
                    </td>
                </tr>
              </template>
            </template>

            </tbody>

        </table>
        <div class="row">
          <button v-if="account_codeId != null" class="button" @click.prevent="addItem">Add Item</button>
          <button  class="button" @click.prevent="addAccount">Add Account Code</button>
        </div>
    <!-- Footer -->
    </div>
</div>
</template>

<script>
  import _ from 'lodash'
  import { Validator } from 'vee-validate';
  import Selectize from 'vue2-selectize'
  import XLSX from 'xlsx';
  const _XLSX = require('xlsx');
  const X = typeof XLSX !== 'undefined' ? XLSX : _XLSX;

  export default {
    components: {Selectize },
    props: {
      readonly: {
        default: true,
        type: Boolean
      },
      codes: Array,
      old: Object
    },
    computed: {
      total: function () {
        return this.model.reduce(function (prev, product) {
          var price = product.unit_price
          if (price != null ) {
            price = price.replace(/\,/g,"");
          }
          product.total_amount = product.quantity * price
          return product.quantity * price
        }, 0)
      },
      total_amount: function () {
        let total = []
        Object.entries(this.model).forEach(([key, val]) => {
          total.push(val.total_amount) // the value of the current key.
        })
        console.log(total)
        // return total
        return total.reduce(function (total, num) { return parseInt(total) + parseInt(num) }, 0)
      },
      accountSettings: function () {
        return {
           options: this.codes,
           optgroups: [
             {id: 'nac', name: 'New Account'},
             {id: 'oac', name: 'Old Account'},
             {id: 'title', name: 'Title'}
           ],
           labelField: 'model',
           valueField: 'id',
           optgroupField: 'make',
           optgroupLabelField: 'name',
           optgroupValueField: 'id',
           optgroupOrder: ['title', 'nac', 'oac'],
           searchField: ['model'],
           plugins: ['optgroup_columns']
        }
      }
    },
    data: () => ({
      active: null,
      account_code: null,
      account_codeId: null,
      model: [],
      accounts: []
    }),
    methods: {
      previewFiles() {
        this.files = this.$refs.myFiles.files
        var file = this.files[0]
        const reader = new FileReader();
        reader.onload = (e) => {
          /* Parse data */
          const bstr = e.target.result;
          const wb = X.read(bstr, {type:'binary'});
          /* Get first worksheet */
          const wsname = wb.SheetNames[0];
          const ws = wb.Sheets[wsname];
          /* Convert array of arrays */
          const data = X.utils.sheet_to_json(ws, {header:1});
          /* Update state */
          this.data = data;
          for (var i = 0; i < this.data.length; i++) {
            // console.log(this.data)
            if(i != 0)
            {
              this.model.push({
                item_description: this.data[i][0],
                new_account_code: this.account_codeId,
                quantity: this.data[i][1],
                unit_measurement: this.data[i][2],
                unit_price: this.data[i][3],
                total_amount: this.data[i][4]
              })

            }
          }
        };
        reader.readAsBinaryString(file)
        this.files = null
        this.$refs.myFiles.files = null
        console.log(this.files)
        console.log(this.$refs.myFiles.files)
      },
      fileInputOpen (){
        $("#file").click()
      },
      addItem () {
        var index = this.model.push({
          item_description: null,
          new_account_code: this.account_codeId,
          quantity: null,
          unit_measurement: null,
          unit_price: null,
          total_amount: null,
        })
      },
      addAccount () {
        $('#add-account-code-modal').addClass('is-visible');
      },
      addCode () {
        var code = $('#accountCode').find(":selected").text();
        // console.log(code);
        var codeId = $('#accountCode').find(":selected").val();

        var codeId = codeId.replace("old-", "");
        var codeId = codeId.replace("title-", "");
        this.account_code = code;
        this.account_codeId = codeId;
        // this.account_code = code;
        // alert(code)
        $('#add-account-code-modal').removeClass('is-visible');

        this.accounts.push({
          code: code,
          id: this.account_codeId
        })
      },
      removeCode (code, index)
      {
        // delete this.accounts[index];
        this.accounts.pop(index);
        console.log(this.accounts)
        this.account_codeId = null;
      },
      closeModal () {
        $('#add-account-code-modal').removeClass('is-visible');
      },
      deleteItem (index) {
        this.active = null
        this.model.splice(index, 1)
      }
    },
    created () {

      // this.model.push(...this.addresses)

      // if (!_(this.old).isEmpty()) {
      //   this.model.splice(0, this.model.length)

      //   if (this.old.items) {
      //     this.old.items.forEach(items => {
      //       console.log(items)
      //       this.model.push(_.clone(items))
      //     })

      //     this.active = 0
      //   }

      //   setTimeout(() => {
      //     this.$validator.validateAll()
      //   }, 200)
      // }

    }
  }
</script>
