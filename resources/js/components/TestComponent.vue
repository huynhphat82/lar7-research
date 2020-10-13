<template>
  <div>
    <div>Test VueJS</div>
    <p v-color:bg="'green'">{{ content | capitalize }}</p>
    <p v-highlight>Hightlight from plugin</p>
    <p class="css">CSS For Component</p>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        content: 'this is content',
      };
    },
    mounted() {
      console.log("Test component mounted");
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
