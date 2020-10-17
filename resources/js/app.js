
require('./bootstrap');

window.Vue = require('vue');

import MyMixin from './mixins';
import MyPlugin from './plugins';
import { BEHttp } from './services';

import './directives';
import './pipes';
import AppError from './services/common/AppError';

// register global mixin
Vue.mixin(MyMixin);

// register plugin
Vue.use(MyPlugin, { someOption: true });

// inject http to vue
Vue.prototype.$http = BEHttp;

// register all components
window.registerComponents(Vue);

Vue.mixin({
  data() {
    return {
      errors: new AppError(ErrorServer),
    };
  },
})

const app = new Vue({
  el: '#app',
  provide() {
    return {

    };
  },
  data() {
    return {

    };
  },
});
