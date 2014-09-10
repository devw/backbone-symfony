/*global define*/
define([
    'require',
    'underscore',
    'backbone',
    'tpl!templates/sidebar/links.hbs',
    '../../app'
], function (require, _, Backbone, linksTemplate, app) {
    "use strict";
    return Backbone.View.extend({

        className: 'widget widget-links',

        events: {
            'click  [data-tid="invite"]': 'invite'
        },

        render: function () {
            this.$el.html(linksTemplate({
                'highfive_support_mail': 'support@high5now.com'
            }));

            return this;
        },

        invite: function (event) {
            event.preventDefault();

            require(['../../utils/backendController', './inviteView'], function (backendController, InviteView) {
                backendController.getInvitationView().done(function (responseTxt) {
                    app.vent.trigger('showModal', new InviteView({content: responseTxt}));
                });
            });
        }
    });
});
