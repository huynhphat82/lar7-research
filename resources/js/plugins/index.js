
const defaultOptions = {
  someOption: 50
};

// create a plugin
const MyPlugin = {
  // called by Vue.use(MyPlugin)
  install(Vue, options) {
    // merge default options with arg options
    let _options = { ...defaultOptions, ...options };

    console.log(_options)

    // 1. Thêm phương thức hoặc thuộc tính cấp toàn cục
    // in component, we use Vue.myGlobalMethod)
    Vue.myGlobalMethod = function () {
      console.log('This is myGlobalMethod from plugin')
    };

    // 2. Thêm một directive cấp toàn cục
    Vue.directive('highlight', function (el, binding) {
      el.style.color = binding.value || 'white';
    });

    // 3. Thêm một số tùy chọn cho component
    Vue.mixin({
      created: function () {

      },
      methods: {
        $mixinPlugin() {
          console.log('This [$mixinPlugin] method is from plugin.');
        }
      },
    });

    // 4. Thêm một phương thức đối tượng
    // (in component, we use this.$myMethod)
    Vue.prototype.$myMethod = function (methodOptions) {
      console.log('This is $myMethod from plugin')
    };
  }
};

export default MyPlugin;
