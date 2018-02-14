<template>
<div class="custom-selection"
  :class="{ 'custom-selection--has-selected' : selected && !tagging }">
  <a @click.stop="removeSelected" class="custom-selection__remove" v-if="selected && !tagging">
   <i class="nc-icon-outline ui-1_simple-remove"></i>
  </a>
  <ul class="custom-input-tags"
    :class="{ 'disabled': disabled }"
    v-if="tagging"
    @click="toggle">
    <li class="custom-input-tags-placeholder" v-if="!tags.length">{{ placeholder }}</li>
    <li class="custom-input-tags-item" v-for="(tag, index) in tags">
      {{ tag.label }}
      <a @click.stop="removeTag(index)"
      class="remove-input-tags-item-btn">
        <i class="nc-icon-mini ui-1_simple-remove"></i>
      </a>
    </li>
  </ul>
  <input class="custom-input"
    type="text"
    v-if="!tagging"
    :disabled="disabled"
    v-model="selected"
    :placeholder="placeholder"
    @focus="toggle"
    readonly>
  <div class="selection"
    v-scrollbar
    :class="{ 'is-visible': active }">
    <ul class="selection-list">
      <selecta-item
        v-for="option in options"
        :option="option"
        :tagging="tagging"
        :only-child="onlyChild"
        :tags="tags"
        :value-label="valueLabel"
        :parent="_uid"
        :key="option.id"
        label="name">
      </selecta-item>
    </ul>
  </div>
</div>
</template>
<style lang="less">
  .custom-input-tags-item {
    margin-right: 4px!important;
  }
  .custom-input-tags-placeholder {
    list-style: none;
    padding: 1px 5px 4px;
  }
  .custom-input-tags.disabled {
    background: #ebebeb;
    cursor: not-allowed;
  }
  /* TODO: Fix me please ardee */
  .custom-selection--has-selected {
    &::after {
      content: ' '!important
    }
  }
  .custom-selection__remove {
    position: absolute;
    top: 12px;
    color: #cccccc;
    right: 12px;
    z-index: 1;
  }
</style>
<script>
  import SelectaItem from './SelectaItem'
  export default {
    components: {
      SelectaItem
    },
    props: {
      name: String,
      onlyChild: Boolean,
      tagging: Boolean,
      disabled: Boolean,
      options: Array,
      value: {
        type: [Number, Array]
      },
      selectedValue: {
        required: false
      },
      valueLabel: {
        default: 'name'
      },
      placeholder: {
        type: String,
        default: 'Select Something ...'
      }
    },
    data () {
      return {
        active: false,
        focused: false,
        selected: null,
        tags: []
      }
    },
    methods: {
      toggle () {
        if (!this.disabled) {
          this.active = !this.active
        }
      },
      close () {
        this.active = false
      },
      removeSelected () {
        this.selected = null
        this.$emit('input', null)
      },
      handleClickOutside (e) {
        const el = this.$el
        if (!el.contains(e.target)) {
          this.close()
        }
      },
      pushUnique (option) {
        var tagIndex = this
          .tags
          .map(tag => tag.value)
          .indexOf(option.id)
        if (tagIndex >= 0) {
          this.removeTag(tagIndex)
          return
        }
        if (this.tags.length < 5) {
          this.tags.push({
            value: option.id,
            label: option[this.valueLabel]
          })
        }
      },
      removeTag (index) {
        this.tags.splice(index, 1)
        this.$emit('input', this.tags.map(tag => tag.value))
      },
      handleSelect (option) {
        if (this.tagging) {
          this.pushUnique(option)
          this.$emit('input', this.tags.map(tag => tag.value))
        } else {
          this.selected = option[this.valueLabel]
          this.$emit('input', option.id)
          this.close()
        }
      },
      setDefaultValue () {
        var value = this.selectedValue
        if (value) {
          if (this.tagging) {
            this.tags = value
            return
          }
          this.selected = value
        }
      },
      clearDefaultValue () {
        this.tags.splice(0, this.tags.length)
        this.selected = null
      }
    },
    created () {
      this.$eventHub.$on('selected_' + this._uid, this.handleSelect)
      this.setDefaultValue()
    },
    mounted () {
      document.addEventListener('click', this.handleClickOutside)
    },
    destroyed () {
      this.clearDefaultValue()
    }
  }
</script>