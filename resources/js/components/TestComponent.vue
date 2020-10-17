<template>
  <div>
    <div>Test VueJS</div>
    <p v-color:bg="'green'">{{ content | capitalize }}</p>
    <p v-highlight>Hightlight from plugin</p>
    <p class="css">CSS For Component</p>
    <input v-model="p" @change="onChange($event)" name="p" />
    <label v-if="errors.has('p')">{{ errors.first('p') }}</label>
    <button @click="send">Send</button>
  </div>
</template>

<script>
  export default {
    // inject: ['errors'],
    data() {
      return {
        p: '',
        content: 'this is content',
      };
    },
    mounted() {
      console.log("Test component mounted");
      console.log('this.errors => ', this.errors)
    },
    methods: {
      onChange(event) {
        console.log('event => ', event.target.value)
        this.errors.remove('p');
      },
      send() {
        this.$http.get('http://localhost:8000/vue').then(r => {
          console.log('r2 => ', r);
        });
      }
    }
  };
</script>

<style scoped>
  .css {
    font-size: 20px;
    font-weight: bold;
    color: white;
  }
  .red {
    color: red;
  }
</style>
