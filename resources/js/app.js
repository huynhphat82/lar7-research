
require('./bootstrap');

window.Vue = require('vue');

import MyMixin from './mixins';
import MyPlugin from './plugins';
import { BEHttp } from './services';

import './directives';
import './pipes';
import './validation/rules';
import { Validator, ValidatorServer } from './validation/Validator';
import { i18n } from './validation/rules/localize';

// register global mixin
Vue.mixin(MyMixin);

// register plugin
Vue.use(MyPlugin, { someOption: true });

// inject http to vue
Vue.prototype.$http = BEHttp;

// Register validation components globally
Vue.component('Validator', Validator);
Vue.component('ValidatorServer', ValidatorServer);

// register all components
window.registerComponents(Vue);

const app = new Vue({
  el: '#app',
  // i18n,
  provide() {
    return {
      errors: ServerError
    };
  },
  created() {
    console.log('xxx => ', i18n.t('validations.messages.required'))
  },
  data() {
    return {
      errors: ServerError,
      trans: this.$t,
    };
  },
  methods: {
    $t(key, config) {
      return i18n.t(key, config);
    }
  }
});
