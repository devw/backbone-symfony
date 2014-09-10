/*global define*/
define([
    'require',
    'backbone',
    'tpl!templates/sidebar/points.hbs',
    '../../app'
], function (require, Backbone, pointsTemplate, app) {
    "use strict";
    var pointsView = Backbone.View.extend({

        className: 'widget widget-points',

        initialize: function () {
            app.currentUser.on('change', this.render, this);
        },

        events: {
            'click [data-tid="recognize"]': 'recognize'
        },

        render: function () {
            this.$el.html(pointsTemplate(app.currentUser.toJSON()));

            return this;
        },

        recognize: function () {
            if (app.userCollection.length > 1) {
                require(['./recognizeView'], function (RecognizeView) {
                    app.vent.trigger('showModal', new RecognizeView());
                });
            } else {
                require(['../../utils/backendController','./inviteView'], function (backendController, InviteView) {
                    backendController.getInvitationWithoutCoworkersView().done(function (responseTxt) {
                        app.vent.trigger('showModal', new InviteView({content: responseTxt}));
                    });
                });
            }
        }
    });

    return pointsView;
});
