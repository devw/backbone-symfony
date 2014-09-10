/*global define*/
define([
    'backbone',
    'tpl!templates/sidebar/profile.hbs',
    '../../app'
], function (Backbone, profileTemplate, app) {
    "use strict";
    var pointsView = Backbone.View.extend({

        className: 'widget widget-profile',

        initialize: function () {
            app.currentUser.on('change', this.render, this);
        },

        render: function () {
            this.$el.html(profileTemplate(app.currentUser.toJSON()));

            return this;
        }
    });

    return pointsView;
});
