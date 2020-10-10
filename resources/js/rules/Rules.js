import VeeValidate from 'vee-validate';

VeeValidate.extend('between', {
    params: ['mix', 'max'],
    validate: (value, { min, max }) => {
        return value >= min && value <= max;
    },

});


VeeValidate.extend('in', {
    validate: (value, params) => {
        return params.includes(value);
    },
    message: (field, params) => {
        return `The ${field} field must be in ${params.join(',')} `; // _field_
    }
});
