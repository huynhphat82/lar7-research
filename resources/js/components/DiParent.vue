<template>
  <div>
    <slot></slot>
  </div>
</template>

<script>
  export default {
    provide() {
      return {
        'getMap': this.getMap
      }
    },
    data() {
      return {
        content: 'this is parent content',
      };
    },
    mounted() {
      console.log("Test component mounted");
    },
    methods: {
      getMap() {
        console.log('This [getMap] method is from parent component.');
      },
    }
  };
</script>

<style scoped>

</style>
