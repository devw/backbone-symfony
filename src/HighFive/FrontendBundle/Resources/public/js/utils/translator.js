/*global define*/
define([
    'bazinga_translator',
    'bazinga_translator_data'
], function (bazingaTranslator) {
    'use strict';

    return {
        trans: function (message, parameters, domain, locale) {
            var translationDomain = domain || 'messages';

            return bazingaTranslator.get(translationDomain + ':' + message, parameters);
        },
        transChoice: function (message, number, parameters, domain, locale) {
            var translationDomain = domain || 'messages';

            return bazingaTranslator.get(translationDomain + ':' + message, parameters, number);
        },
        setLocale: function (locale) {
            bazingaTranslator.locale = locale;
        },
        getLocale: function () {
            return bazingaTranslator.locale;
        }
    };
});
