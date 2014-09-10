/*global define*/
define([
    'backbone',
    'marionette',
    'bootstrapModal'
], function (Backbone, Marionette) {
    "use strict";
    // this module defines the main application, which will be used by other
    // modules to dispatch or listen to events. It should not define its own
    // logic to avoid circular dependencies.

    var app = new Marionette.Application();

    app.addRegions({
        header: '#header',
        sidebar: '#sidebar',
        main: '#main',
        modal: '#modal'
    });

    //---------------------------------------
    // Modals
    //---------------------------------------
    app.vent.on('showModal', function (view) {
        var modal = app.modal;

        modal.show(view);
        modal.$el.modal({
            show: true,
            keyboard: true,
            backdrop: true
        });

        modal.$el.on('shown', function () {
            $('input:text:visible:first', this).focus();
        });
    });

    app.vent.on('hideModal', function () {
        var modal = app.modal.$el;

        modal.modal('hide');
    });

    //---------------------------------------
    // Bootstrap Tour
    //---------------------------------------
    app.vent.on('start_tour', function () {
        app.tour.start();
    });

    //---------------------------------------
    // BackBone Router
    //---------------------------------------
    app.on("initialize:after", function () {
        // TODO it will be uncomment only when the Backbone.Router will be implemented
//        Backbone.history.start();
    });

    return app;
});
