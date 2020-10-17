import Vue from 'vue';
import { localize, extend } from 'vee-validate';
import VueI18n from 'vue-i18n';
import en from 'vee-validate/dist/locale/en.json';
import validationMessages from 'vee-validate/dist/locale/en';

Vue.use(VueI18n);

localize({
  en: {
    ...en,
    ...require('../../lang/en.js')
  },
});

// set default language as en
localize('en');

function loadLocale(code) {
  return import(`vee-validate/dist/locale/${code}.json`).then(locale => {
    localize(code, locale);
  });
}
console.log('validationMessages => ', validationMessages.messages.required)
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

export {
  i18n
};

// extend('required', {
//   ...required,
//   // the values param is the placeholders values
//   message: (_, values) => i18n.$t('validations.messages.required', values)
// });
