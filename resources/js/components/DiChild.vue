<template>
  <div>
    <input name="child" />
  </div>
</template>

<script>
  export default {
    inject: ['getMap'],
    data() {
      return {
        content: 'this is content',
      };
    },
    created() {
      console.log('this.$root', this.$root);
      console.log('this.$parent', this.$parent);
      this.getMap();
      console.log('this.$root.content [child] => ', this.$root.content);
      console.log('this.$parent.content [child] => ', this.$parent.content);
      console.log(this.$refs.child)
    },
    methods: {

    }
  };
</script>

<style scoped>
  .css {
    font-size: 20px;
    font-weight: bold;
    color: white;
  }
</style>
