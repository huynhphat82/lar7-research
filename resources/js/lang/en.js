export const messages = {
    names: {
        email: 'E-mail Address',
         password: 'Password'
    },
    fields: {
        password: {
            required: 'Password cannot be empty!',
            max: 'Are you really going to remember that?',
            min: 'Too few, you want to get doxed?'
        },
    },
    messages: {
        required: 'this field is required',
        min: 'this field must have no less than {length} characters',
        max: (_, { length }) => `this field must have no more than ${length} characters`
    },
};
