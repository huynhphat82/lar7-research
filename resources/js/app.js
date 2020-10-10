
require('./bootstrap');

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('test-component', require('./components/TestComponent.vue').default);
Vue.component('datepicker', require('./components/DatePickerComponent.vue').default);

const app = new Vue({
    el: '#app',
});
