<template>
  <div>
    <div>Test VueJS</div>
    <p v-color:bg="'green'">{{ content | capitalize }}</p>
    <p v-highlight>Hightlight from plugin</p>
    <p class="css">CSS For Component</p>
    <Validator rules="required" v-slot="{ errors }">
      <input v-model="email" type="text" placeholder="Enter email">
      <span>{{ errors[0] }}</span>
    </Validator>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        content: 'this is content',
        email: '',
      };
    },
    mounted() {
      console.log("Test component mounted");
      console.log('this.$t', this.trans('validations.messages.required'))
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
