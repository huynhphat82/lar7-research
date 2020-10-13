import Http from "./common/Http";
import { HttpCode } from "./common/HttpCode";

const BEHttp = new Http('http://localhost:8000');

BEHttp.setHeaders({
  'X-Requested-With': 'XMLHttpRequest'
});

BEHttp.interceptors([
  config => {
    console.log('config => ', config);
    return config;
  },
  err => {
    console.log('err => ', err)
  }
], [
  response => {
    switch (response.status) {
      case HttpCode.OK:
        break;
      default:
        break;
    }
    return response;
  },
  err => {
    console.log('err => ', err)
  }
]);

export default BEHttp;
