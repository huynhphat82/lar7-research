
require('./bootstrap');

window.Vue = require('vue');

import MyMixin from './mixins';
import MyPlugin from './plugins';
import { BEHttp } from './services';

import './directives';
import './pipes';

// register global mixin
Vue.mixin(MyMixin);

// register plugin
Vue.use(MyPlugin, { someOption: true });

// inject http to vue
Vue.prototype.$http = BEHttp;

// register all components
window.registerComponents(Vue);

const app = new Vue({
  el: '#app',
});
