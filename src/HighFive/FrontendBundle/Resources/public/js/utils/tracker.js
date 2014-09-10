/*global define, window*/
define([
    '../app'
], function (app) {
    "use strict";

    function track(record) {
        var kmq = window._kmq || (window._kmq = []);
        kmq.push(record);
    }

    app.vent.on('track', function (name, properties) {
        track(['record', name, properties]);
    });

    app.vent.on('current_user_fetched', function () {
        track(['identify', app.currentUser.get('email')])
    });
});
