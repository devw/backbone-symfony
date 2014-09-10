/*global define*/
define([
    'require',
    'underscore',
    'marionette',
    'tpl!templates/header/notification.hbs',
    '../../utils/translator',
    '../../app'
], function (require, _, Marionette, notificationTemplate, translator, app) {
    "use strict";
    return Marionette.ItemView.extend({

        initialize: function () {
            _.bindAll(this, 'onSuccess', 'onError', 'doCloseNotification');
        },

        className: 'notification-item',

        template: notificationTemplate,

        tagName: 'li',

        serializeData: function () {
            var modelJSON = this.model.toJSON();

            return _.extend({
                'translated_message': translator.trans(modelJSON.message, modelJSON.parameters),
                'sender_user': this.model.get('sender_user')
            }, modelJSON);

        },

        events: {
            'click [data-tid="read"]': 'closeNotification'
        },

        closeNotification: function () {
            require(['../../utils/backendController'], this.doCloseNotification);
        },

        doCloseNotification: function (backendController) {
            this.model.set({
                read: true
            });
            backendController.closeNotification(this.model).done(this.onSuccess).fail(this.onError);
        },

        onSuccess: function () {
            this.remove();
            app.vent.trigger('closeNotification', this.model);
        },

        onError: function () {
            // TODO we need to implement a module for getting the Server Error Messages
        }

    });
});
