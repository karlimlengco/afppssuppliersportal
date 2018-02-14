<template>
  <li class="selection-item"
    :class="{ 'has-child': hasChild, 'is-open': active }">
    <span
      @click.prevent="select"
      class="selection-label"
      :class="{ 'has-check': tagging, 'is-selected': isSelected }">
      {{ option[label] }}
      <i class="selection-child-trigger nc-icon-mini"
        v-if="hasChild"
        @click.stop="toggle"
        :class="[ active ? 'ui-2_small-delete' : 'ui-2_small-add' ]">
      </i>
    </span>
    <ul class="child-selection" :class="{ 'is-visible': active }" v-if="hasChild">
      <selecta-item
        v-for="model in children"
        :option="model"
        :tagging="tagging"
        :tags="tags"
        :value-label="valueLabel"
        :only-child="onlyChild"
        :parent="parent"
        :key="model.id"
        label="name">
      </selecta-item>
    </ul>
  </li>
</template>
<style>
  .has-check.selected::before {
    color: rgba(46, 204, 113,1.0);
  }
</style>
<script>
  import SelectaItem from './SelectaItem'
  export default {
    components: {
      SelectaItem
    },
    props: {
      option: Object,
      tags: Array,
      onlyChild: Boolean,
      valueLabel: {
        default: 'name'
      },
      label: String,
      parent: Number,
      tagging: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return { active: false }
    },
    methods: {
      select () {
        if (this.hasChild && this.onlyChild) {
          return false
        }
        this
          .$eventHub
          .$emit('selected_' + this.parent, this.option)
      },
      toggle () {
        this.active = !this.active
      }
    },
    computed: {
      isSelected () {
        return this.tags
          .map(tag => tag.value)
          .indexOf(this.option.id) >= 0
      },
      hasChild () {
        return this.children && this.children.length
      },
      children () {
        if (this.option.children && this.option.children.length) {
          return this.option.children
        }
        return this.option.descendants
      }
    },
    beforeCreate () {
      this.$options.components.SelectaItem = require('./SelectaItem.vue')
    }
  }
</script>