import VeeValidate from 'vee-validate';
import VueI18n from 'vue-i18n';
import en from 'vee-validate/dist/locale/en.json';

VeeValidate.localize({
    en: {...en, ...require('../lang/en.js')},
});

// set default language as en
VeeValidate.localize('en');

function loadLocale(code) {
    return import(`vee-validate/dist/locale/${code}.json`).then(locale => {
      localize(code, locale);
    });
}

// Since vee-validate default messages are
// compatible with I18n format
// you can merge them if needed.
const i18n = new VueI18n({
    locale: 'en',
    messages: {
      en: {
        validations: validationMessages
      }
    }
});

VeeValidate.extend('required', {
    ...required,
    // the values param is the placeholders values
    message: (_, values) => i18n.$t('validations.messages.required', values)
});
