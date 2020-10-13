import axios from "axios"
import Response from "./Response";

export default class Http {

  /**
   * constructor
   *
   * @param {object} options
   * {
   *    baseURL: <string: base url>, // or baseUrl
   *    timeout: <int: 60000>, // ms
   *    withCredentials: <boolean: false>,
   *    auth: <object>,
   *    maxRedirects: <int: 5>,
   *    responseType: <object>
   * }
   * @return void
   */
  constructor (options = {}) {
    this.options = this._filterOptions(this._resolveOptions(options));
  }

  setConfig = (config) => {
    if (typeof config === 'object') {
      this.options = { ...this.options, ...config };
    }
    return this;
  }

  request = async (url, bodyParams = {}, queryParams = {}, method = 'GET') => {
    try {
      let responseResult = await axios.request(this._getConfig(url, bodyParams, queryParams, method));
      return Response.success(responseResult.data, responseResult.status);
    } catch (e) {
        return (e.response)
                ? Response.error(e.response.data, e.response.status)
                : Response.error(e.message, 442);
    }
  };

  get    = (url, query = {}) => this.request(url, {}, query);
  post   = (url, body = {}, query = {}) => this.request(url, body, query, 'POST');
  put    = (url, body = {}, query = {}) => this.request(url, body, query, 'PUT');
  patch  = (url, body = {}, query = {}) => this.request(url, body, query, 'PATCH');
  delete = (url, body = {}, query = {}) => this.request(url, body, query, 'DELETE');
  head   = (url, body = {}, query = {}) => this.request(url, body, query, 'HEAD');

  _getConfig = (url, bodyParams, queryParams, method) => {
    let defaultConfigs = this._defaultConfigs(url, bodyParams, queryParams, method);
    if (Object.keys(this.options).length > 0) {
      return { ...defaultConfigs, ...this.options };
    }
    return defaultConfigs;
  }

  _resolveOptions = (options) => {
    if (typeof options === 'object') {
      return options;
    }
    return (typeof options === 'string') ? { baseURL: options } : {};
  }

  _filterOptions = (options) => {
    let headersAllowed = this._headersAllowed();
    let _options = {};
    for (let key in options) {
      key = (key === 'baseUrl') ? 'baseURL' : key;
      if (headersAllowed.indexOf(key) !== false) {
        _options[key] = options[key];
      }
    }
    return _options;
  }

  _headersAllowed = () => ([
    'baseURL', 'baseUrl', 'timeout', 'withCredentials', 'auth', 'maxRedirects', 'responseType'
  ]);

  _defaultHeaders = () => ({
    "Content-Type": "application/json",
    "Accept": "application/json"
  });

  _defaultConfigs = (url, bodyParams = {}, queryParams = {}, method = 'GET') => ({
    // url for the request
    url: url,
    // request method
    method: method, // default
    // base url will be prepended to `url` unless `url` is absolute
    baseURL: '',
    // `transformRequest` allows changes to the request data before it is sent to the server
    // This is only applicable for request methods 'PUT', 'POST', and 'PATCH'
    // The last function in the array must return a string, an ArrayBuffer, or a Stream
    transformRequest: [function (data) {
      // Do whatever you want to transform the data
      return data;
    }],
    // `transformResponse` allows changes to the response data to be made before it is passed to then/catch
    transformResponse: [function (data) {
      // Do whatever you want to transform the data
      return data;
    }],
    // `headers` are custom headers to be sent
    headers: this._defaultHeaders(),
    // `params` are the URL parameters to be sent with the request
    params: queryParams,
    // `paramsSerializer` is an optional function in charge of serializing `params`
    // paramsSerializer: function(params) {
    //     return Qs.stringify(params, {arrayFormat: 'brackets'})
    // },
    // `data` is the data to be sent as the request body
    // Only applicable for request methods 'PUT', 'POST', and 'PATCH'
    // When no `transformRequest` is set, must be of one of the following types:
    // - string, plain object, ArrayBuffer, ArrayBufferView, URLSearchParams
    // - Browser only: FormData, File, Blob
    // - Node only: Stream
    data: bodyParams,
    // `timeout` specifies the number of milliseconds before the request times out.
    timeout: 60000,
    // `withCredentials` indicates whether or not cross-site Access-Control requests
    withCredentials: false, // default
    // `auth` indicates that HTTP Basic auth should be used, and supplies credentials.
    // This will set an `Authorization` header, overwriting any existing
    // `Authorization` custom headers you have set using `headers`.
    auth: {},
    // `responseType` indicates the type of data that the server will respond with
    // options are 'arraybuffer', 'blob', 'document', 'json', 'text', 'stream'
    responseType: 'json', // default
    // `xsrfCookieName` is the name of the cookie to use as a value for xsrf token
    xsrfCookieName: 'XSRF-TOKEN', // default
    // `xsrfHeaderName` is the name of the http header that carries the xsrf token value
    xsrfHeaderName: 'X-XSRF-TOKEN', // default
    // `progress` allows handling of progress events for 'POST' and 'PUT uploads' as well as 'GET' downloads
    progress: function (progressEvent) {
      // Do whatever you want with the native progress event
    },
    // `maxContentLength` defines the max size of the http response content allowed
    // maxContentLength: 2000,
    // `validateStatus` defines whether to resolve or reject the promise for a given
    // HTTP response status code. If `validateStatus` returns `true` (or is set to `null`
    // or `undefined`), the promise will be resolved; otherwise, the promise will be
    // rejected.
    // validateStatus: function (status) {
    //     return status >= 200 && status < 300; // default
    // },
    // `maxRedirects` defines the maximum number of redirects to follow in node.js.
    // If set to 0, no redirects will be followed.
    maxRedirects: 5, // default
  });
}
