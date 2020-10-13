
require('./bootstrap');

window.Vue = require('vue');

import MyMixin from './mixins';
import MyPlugin from './plugins';

import './directives';
import './pipes';

// register global mixin
Vue.mixin(MyMixin);

// register plugin
Vue.use(MyPlugin, { someOption: true });

// inject http to vue
Vue.prototype.$http = window.axios || require('axios');

// register all components
window.registerComponents(Vue);

const app = new Vue({
  el: '#app',
});
