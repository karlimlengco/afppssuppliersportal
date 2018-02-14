<template>
  <li class="selection-item"
    @click.stop="toggle"
    :class="{ 'active ': isActive }">
    <span class="selection-label multi-column">
      <div class="col">{{ option.old_account_code || '&mdash;' }}</div>
      <div class="col">{{ option.new_account_code }}</div>
      <div class="col">{{ option.name }}</div>
    </span>
  </li>
</template>
<script>
  export default {
    props: {
      option: null,
      label: String,
      selected: String,
      index: Number
    },
    data () {
      return { active: false }
    },
    computed: {
      isActive () {
        return this.option.expense_class_id
      }
    },
    mounted:function (){
    },
    methods: {
      toggle () {
        this.option.index = this.index
        this.$emit('selected', this.option)
        this.active = !this.active
      }
    }
  }
</script>