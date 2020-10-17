export default class AppError {

  errors = {};

  constructor(errors) {
    this.errors = errors || {};
    this.errors.a = 'error message';
  }

  has(key) {
    return this.errors.hasOwnProperty(key);
  }

  first(key = null) {
    let keys = Object.keys(this.errors);
    if (keys.length <= 0) {
      return null;
    }
    let msg = this.errors[key || keys[0]];
    return Array.isArray(msg) ? msg[0] : msg;
  }

  get(key = null) {
    return this.errors[key];
  }

  only(keys = []) {
    if (!Array.isArray(keys)) {
      throw new Error('Parameters must be an array.');
    }
    return keys.reduce((carry, key) => {
      carry[key] = this.errors[key];
      return carry;
    }, {});
  }

  all() {
    return this.errors;
  }

  remove(keys = []) {
    try {
      keys = !Array.isArray(keys) ? [keys] : keys;
      keys.forEach(key => {
        if (this.errors.hasOwnProperty(key)) {
          delete this.errors[key];
        }
      });
      return true;
    } catch(err) {
      return false;
    }
  }

  reset() {
    this.errors = {};
    return this;
  }
}
