/*global define, require*/
define([
    'jquery',
    './app',
    './views/header',
    './views/sidebar',
    './views/main/wallView',
    './collections/users',
    // Additional modules which should be loaded to initialize the app without being used
    // as dependency in this module directly go below
    'jqueryCookie',
    './utils/tracker'
], function ($, app, HeaderView, SidebarView, WallView, UserCollection) {
    "use strict";
    // this module defines the main entry point of the application. It loads
    // the different modules and then returns the main application to allow
    // starting it.
    app.addInitializer(function () {
        var currentUserId = parseInt($('body').attr('data-user-id'), 10);

        app.userCollection = new UserCollection();
        app.userCollection.fetch({
            success: function () {
                app.currentUser = app.userCollection.where({id: currentUserId})[0];
                app.vent.trigger('current_user_fetched');
                app.main.show(new WallView());
                app.header.show(new HeaderView({currentUser: app.currentUser}));
                app.sidebar.show(new SidebarView());
                if ($.cookie('tour_end') !== 'yes') {
                    require(['./utils/tour'], function (tour) {
                        app.tour = tour;
                        app.vent.trigger('start_tour');
                    });
                }
            }
        });
    });

    return app;
});
