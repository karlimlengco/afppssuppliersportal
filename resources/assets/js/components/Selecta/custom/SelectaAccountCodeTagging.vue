<template>
<div class="custom-selection custom-selection--account-code">
  <input class="custom-input"
    type="text"
    v-model="query"
    :placeholder="placeholder"
    @click="toggle">
  <div class="selection-head" :class="{ 'is-visible': active }">
    <span class="selection-label multi-column">
      <div class="col">Old Account</div>
      <div class="col">New Account</div>
      <div class="col">Account Title</div>
    </span>
  </div>
  <div class="selection with-head"
    :class="{ 'is-visible': active }">
    <ul class="selection-list">
      <selecta-item
        v-for="(opt, index) in option"
        :option="opt"
        :index="index"
        :selected="selected"
        @selected="handleSelect"
        :key="opt.id"
        label="name">
      </selecta-item>
      <div class="selection-list__no-content" v-if="!option.length">No result found for <strong>{{ query }}</strong>.</div>
    </ul>
  </div>
</div>
</template>
<style >
  .custom-selection {
  border: solid 1px #cccccc;
  position: relative;
}
.custom-selection:after {
  position: absolute;
  font-family: "Nucleo Outline";
  content: '\ea7f';
  top: 50%;
  right: 10px;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  pointer-events: none;
  color: #949494;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}
.custom-selection .custom-input {
  position: relative;
  border: none;
  background: transparent;
  width: 100%
}
.custom-selection .custom-input-tags {
  display: block;
  list-style: none;
  overflow: auto;
  height: auto;
  margin: 0;
  padding: 10px 10px 6px 10px;
}
.custom-selection .custom-input-tags .custom-input-tags-item {
  float: left;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  margin: 0 4px 4px 0;
  padding: 4px 8px;
  border-radius: 3px;
  background: #44AF69;
  color: #FFFFFF;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}
.custom-selection .custom-input-tags .custom-input-tags-item .remove-input-tags-item-btn {
  display: inline-block;
  font-size: 9px;
  margin-left: 4px;
  color: #FFFFFF;
  zoom: 1;
  filter: alpha(opacity=50);
  -webkit-opacity: 0.5;
  -moz-opacity: 0.5;
  opacity: 0.5;
}
.custom-selection .custom-input-tags .custom-input-tags-item .remove-input-tags-item-btn:hover {
  zoom: 1;
  filter: alpha(opacity=100);
  -webkit-opacity: 1;
  -moz-opacity: 1;
  opacity: 1;
}
.custom-selection .selection-head {
  position: absolute;
  left: -1px;
  z-index: 99;
  height: 40px;
  -lh-property: 0;
width:calc(100% + 2px);
;
  border: solid 1px #cccccc;
  background: #FFFFFF;
  display: none;
}
.custom-selection .selection-head.is-visible {
  display: block;
}
.custom-selection .selection-head .selection-label {
  display: block;
  padding: 5px 15px;
}
.custom-selection .selection-head .selection-label.multi-column {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
      -ms-flex-direction: row;
          flex-direction: row;
  padding: 0;
  margin: 0;
  border-bottom: solid 1px #ebebeb;
}
.custom-selection .selection-head .selection-label.multi-column .col {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  -ms-flex-negative: 0;
      flex-shrink: 0;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  width: 160px;
  padding: 13px 15px;
  line-height: 1.2;
  color: #FFFFFF;
  background: #c5c5c5;
  border-right: solid 1px #ebebeb;
}
.custom-selection .selection-head .selection-label.multi-column .col:last-child {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-flex: 1;
      -ms-flex-positive: 1;
          flex-grow: 1;
  border-right: none;
}
.custom-selection .selection-head .selection-label.multi-column:last-child {
  border: none;
}
.custom-selection .selection {
  position: absolute;
  left: -1px;
  z-index: 99;
  -lh-property: 0;
width:calc(100% + 2px);
;
  max-height: 150px;
  height: auto;
  overflow-y: scroll;
  border: solid 1px #cccccc;
  background: #FFFFFF;
  display: none;
}
.custom-selection .selection.is-visible {
  display: block;
}
.custom-selection .selection.with-head {
  margin-top: 39px;
}
.custom-selection .selection .no-result-message {
  display: block;
  color: #949494;
  font-weight: 300;
  padding: 8px 15px;
}
.custom-selection .selection .selection-list {
  display: block;
  margin: 0;
  padding: 0;
  list-style: none;
}
.custom-selection .selection .selection-list .selection-item {
  display: block;
  position: relative;
  margin: 0;
  padding: 0;
  cursor: pointer;
  font-size: 14px;
  border-top: solid 1px #eeeeee;
}
.custom-selection .selection .selection-list .selection-item.active {
  background: #44AF69;
  color: #FFFFFF;
}
.custom-selection .selection .selection-list .selection-item.active:hover {
  background: #44AF69;
}
.custom-selection .selection .selection-list .selection-item.active .selection-label {
  background: #44AF69;
  color: #FFFFFF;
}
.custom-selection .selection .selection-list .selection-item.active .selection-label:hover {
  background: #44AF69;
}
.custom-selection .selection .selection-list .selection-item .selection-label {
  display: block;
  padding: 5px 15px;
}
.custom-selection .selection .selection-list .selection-item .selection-label i {
  float: right;
  font-weight: 300;
  -webkit-transform: translate(2px, 1px);
  transform: translate(2px, 1px);
}
.custom-selection .selection .selection-list .selection-item .selection-label:hover {
  background: #eeeeee;
}
.custom-selection .selection .selection-list .selection-item .selection-label.has-check:before {
  display: inline-block;
  content: '\ea22';
  font-family: "Nucleo Mini";
  width: auto;
  margin: 0;
  padding: 0;
  -webkit-transform: translate(-2px, 2px);
  transform: translate(-2px, 2px);
  color: #bbbbbb;
}
.custom-selection .selection .selection-list .selection-item .selection-label.is-selected:before {
  color: #6e6e6e;
}
.custom-selection .selection .selection-list .selection-item .selection-label.multi-column {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
      -ms-flex-direction: row;
          flex-direction: row;
  padding: 0;
  margin: 0;
  border-bottom: solid 1px #eeeeee;
}
.custom-selection .selection .selection-list .selection-item .selection-label.multi-column .col {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-negative: 0;
      flex-shrink: 0;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  width: 160px;
  padding: 8px 15px;
  line-height: 1.2;
  border-right: solid 1px #eeeeee;
}
.custom-selection .selection .selection-list .selection-item .selection-label.multi-column .col:last-child {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-flex: 1;
      -ms-flex-positive: 1;
          flex-grow: 1;
}
.custom-selection .selection .selection-list .selection-item .selection-label.multi-column:last-child {
  border: none;
}
.custom-selection .selection .selection-list .selection-item.has-child {
  /* child selection */
}
.custom-selection .selection .selection-list .selection-item.has-child ul {
  margin: 0;
  padding: 0;
}
.custom-selection .selection .selection-list .selection-item.has-child ul li .selection-label {
  padding-left: 35px;
}
.custom-selection .selection .selection-list .selection-item.has-child ul li li .selection-label {
  padding-left: 55px;
}
.custom-selection .selection .selection-list .selection-item.has-child ul li li li .selection-label {
  padding-left: 75px;
}
.custom-selection .selection .selection-list .selection-item.has-child .child-selection {
  display: none;
}
.custom-selection .selection .selection-list .selection-item.has-child .child-selection.is-visible {
  display: block;
}

</style>
<script>
  import $ from 'jquery'
  import axios from 'axios'
  import SelectaItem from './SelectAccountCodeItem'
  import { debounce } from 'lodash'
  import { mapGetters, mapActions } from 'vuex'
  export default {
    components: {
      SelectaItem
    },
    props: {
      url: String,
      name: String,
      value: String,
      accountCodeSelection: String,
      selectedval: String,
      selectedValue: {
        required: false
      }
    },
    computed: {

      placeholder () {
        return this.selected ?
          `Selected ${this.placeholders}` :
          'Select Account Code'
      }
    },
    watch: {
      query: debounce (function(query) {
        this.fetchRecords(query)
      }, 500),
      value: function(){
        this.selected = this.value
        var oldval = this.selectedCount
        this.placeholders = oldval[0].name
      }
    },
    data () {
      return {
        active: false,
        query: null,
        selected: null,
        placeholders: null,
        options: []
      }
    },
    methods: {
      toggle () {
        this.active = !this.active
      },
      close () {
        this.active = false
        this.$emit('input', this.selected)
      },
      handleClickOutside (e) {
        const el = this.$el
        if (!el.contains(e.target)) {
          this.close()
        }
      },
      handleSelect (option) {
        this.active = false
        // If the option has expense_class_id immediately remove
        // the item from the database record instead
        this.placeholders = option.name
        this.selected = option.id
        this.query = ''
        this.$emit('input', this.selected)
      },
      fetchRecords (query = '') {
        console.log(this.option)
        // this.dropAccounts(query)
        // axios.get(this.url, { params: {
        //   query
        // }}).then(response => {
        //   this.options = response.data.data
        // })
      },
      setDefaultValue () {
        var value = this.selectedValue
        if (value) {
          this.selected = value
        }
      },
      clearDefaultValue () {
        this.selected = null
      }
    },
    created () {
      this.setDefaultValue()
      this.fetchRecords()
      // this.$eventHub.$on('selected', this.handleSelect)
    },
    destroyed () {
      this.clearDefaultValue()
    },
    mounted () {
      this.fetchRecords()
      document.addEventListener('click', this.handleClickOutside)
    }
  }
</script>