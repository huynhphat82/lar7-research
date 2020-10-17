import { extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import { messages } from 'vee-validate/dist/locale/en.json';

// load default rules
Object.keys(rules).forEach(rule => {
  extend(rule, {
    ...rules[rule], // copies rule configuration
    message: messages[rule] // assign message
  });
});

// define new rules
extend('between', {
  params: ['mix', 'max'],
  validate: (value, { min,  max }) => {
    return value >= min && value <= max;
  },

});

extend('in', {
  validate: (value, params) => {
    return params.includes(value);
  },
  message: (field, params) => {
    return `The ${field} field must be in ${params.join(',')} `; // _field_
  }
});
