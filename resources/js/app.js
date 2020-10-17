
require('./bootstrap');

window.Vue = require('vue');

import MyMixin from './mixins';
import MyPlugin from './plugins';
import { BEHttp } from './services';
import { Validator, ValidatorServer } from './validation/Validator';
import { i18n } from './validation/rules/localize';
import AppError from './services/common/AppError';

import './directives';
import './pipes';
import './validation/rules';

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

Vue.mixin({
  data() {
    return {
      errors: new AppError(ErrorServer),
    };
  },
  methods: {
    t(key, config) {
      return i18n.t(key, config);
    },
    trans() {
      return this.t;
    },
  },
  created() {
    console.log('xxx => ', i18n.t('validations.messages.required'))
  },
})

const app = new Vue({
  el: '#app',
  provide() {
    return {
      $errors: this.errors,
    };
  },
  data() {
    return {
      //
    };
  },
  methods: {
    //
  }
});
