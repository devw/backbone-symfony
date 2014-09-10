/*global define, window, setTimeout*/
(function () {
    'use strict';
    define([
        'jasmine',
        'jasmineHtml',
        'jasmineJquery',
        'handlebars',
        './views/header.spec',
        './views/sidebar.spec',
        './views/sidebar/pointsView.spec',
        './views/sidebar/feedbackView.spec',
        './views/sidebar/profileView.spec',
        './views/sidebar/recognizeView.spec',
        './views/main/wallView.spec',
        './views/header/notificationView.spec',
        './models/user.spec',
        './models/message.spec',
        './models/notification.spec'
    ], function (jasmine) {
        var initialize = function () {
            var jasmineEnv = jasmine.getEnv(),
                htmlReporter = new jasmine.HtmlReporter(),
                currentWindowOnload = window.onload,
                execJasmine = function execJasmine() {
                    jasmineEnv.execute();
                };

            jasmineEnv.updateInterval = 1000;
            jasmineEnv.addReporter(htmlReporter);
            jasmineEnv.specFilter = function (spec) {
                return htmlReporter.specFilter(spec);
            };

            setTimeout(function () {
                if (currentWindowOnload) {
                    currentWindowOnload();
                }
                execJasmine();
            }, 0);

        };

        return {
            initialize: initialize
        };
    });
}());
