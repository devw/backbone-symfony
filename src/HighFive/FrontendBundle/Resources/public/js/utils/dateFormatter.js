/*global define*/
define([
    'moment',
    './translator',
    'momentFr'
], function (moment, translator) {
    'use strict';

    moment.lang(translator.getLocale());

    return {
        formatHumanReadableDate: function (date) {
            if (date) {
                return moment(date, "YYYY-MM-DDTHH:mm:ssZ").fromNow();
            }
        }
    }
});
