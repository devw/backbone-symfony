/*global define, Tour*/
define([
    '../app',
    './translator',
    'bootstrapTour'
], function (app, translator) {
    "use strict";

    var tour = new Tour();

    tour.addStep({
        element: '[data-tid="recognize"]',
        title: translator.trans('recognition.tour.recognize-title'),
        content: translator.trans('recognition.tour.recognize-content')
    });

    tour.addStep({
        element: '[data-tid="invite"]',
        title: translator.trans('invitation.tour.title'),
        content: translator.trans('invitation.tour.content')
    });

    return tour;
});
