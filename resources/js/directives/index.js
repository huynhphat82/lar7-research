
import Vue from 'vue';

/** v-focus directive */
Vue.directive("focus", {
  /**
   * <div id="hook-arguments-example" v-demo:foo.a.b="message"></div>
   * @param {*} el
   * @param {object} binding
   * {
  *      name: <ten directive>, // demo
   *      value: <giá trị truyền vào>, // result of 'message' expression
   *      oldValue: <giá trị cũ>,
   *      expression: <biểu thức của binding>, // message
   *      arg: <tham số truyền vào directive>, // foo
   *      modifiers: <một object chứa các modifier> // {"a": true, "b": true}
   * }
   * @param {*} vnode
   */
  bind(el, binding, vnode) {
    // chỉ gọi 1 lần khi directive được bind vào element
  },
  inserted(el) {
    // được gọi khi element đã được chèn vào element cha (nhưng ko chắc phần tử cha đã tồn tại trong DOM)
    el.focus();
  },
  updated() {
    // được gọi sau khi VNode của phần tử chứa đã cập nhật, nhưng có thể trước khi các phần tử con được cập nhật.
  },
  componentUpdated() {
    // được gọi sau khi VNode của phần tử chứa và VNode của toàn bộ các phần tử con đã cập nhật.
  },
  unbind() {
    // chỉ gọi 1 lần khi directive được unbind khỏi phần tử.
  },
});

// shorthand declaire (only using hooks: bind & update)
Vue.directive('color-swatch', function (el, binding) {
  el.style.backgroundColor = binding.value;
});

// shorthand declaire (only using hooks: bind & update)
Vue.directive('color-text', function (el, binding) {
  el.style.color = binding.value;
});

// shorthand declaire (only using hooks: bind & update)
Vue.directive('color', function (el, binding) {
  let type = 'color';
  if (binding.arg == 'bg') {
    type = 'background';
  }
  el.style[type] = binding.value;
});
