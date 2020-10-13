import upperFirst from 'lodash/upperFirst';
import camelCase from 'lodash/camelCase';

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/**
 * Register all components of Vue automatically
 * @param {string} pathToComponents
 * @param {object} Vue
 */
window.registerComponents = function (Vue) {
  const requireComponent = require.context(
    // Đường dẫn tương đối của thư mục component
    './components',
    // có tìm component trong các thư mục con hay không: true: có | false: không
    true,
    // regular expression để tìm các file component cơ sở (/[A-Z]\w+\.(vue|js)$/)
    /[A-Z]\w+\.(vue)$/
  );
  requireComponent.keys().forEach(fileName => {
    // Lấy cấu hình của component
    const componentConfig = requireComponent(fileName);
    // Bỏ các subfolder nếu có
    fileName = './' + fileName.split('/').pop();
    // Lấy tên của component dùng PascalCase
    const componentName = upperFirst(
      camelCase(
        // Bỏ phần đầu `'./` và đuôi file
        fileName.replace(/^\.\/(.*)\.\w+$/, '$1')
      )
    );
    // Đăng ký các component cấp toàn cục
    Vue.component(componentName, componentConfig.default || componentConfig);
  });
};
