<template>
  <li class="selection-item"
    @click.stop="toggle"
    :class="{ 'is-open': active }">
    <span class="selection-label multi-column">
      <div class="col">{{ option.old_account_code || '&mdash;' }}</div>
      <div class="col">{{ option.new_account_code }}</div>
      <div class="col">{{ option.name }}</div>
    </span>
  </li>
</template>
<script>
  export default {
    data () {
      return { active: false }
    },
    methods: {
      toggle () {
        if (!this.hasChild) {
          this.$emit('selected', this.option)
          return
        }
        this.active = !this.active
      }
    },
    props: { option: Object, label: String }
  }
</script>