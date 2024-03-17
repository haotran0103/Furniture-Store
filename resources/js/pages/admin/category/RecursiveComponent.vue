<template>
    <select v-model="selected" @change="handleChange">
      <option v-for="option in options" :key="option.id" :value="option.id">
        {{ optionPrefix(option.level) }} {{ option.name }}
      </option>
    </select>
  </template>
  
  <script>
  export default {
    props: {
      options: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        selected: null
      };
    },
    methods: {
      optionPrefix(level) {
        return '--'.repeat(level);
      },
      handleChange() {
        this.$emit('change', this.selected);
      }
    }
  };
  </script>
  