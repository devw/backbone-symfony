/*global define*/
define([
    'underscore',
    'marionette',
    'tpl!templates/header/main.hbs',
    '../utils/backendRouter',
    '../collections/notifications',
    './header/notificationView',
    '../app',
    'bootstrapDropdown'
], function (_, Marionette, headerTemplate, backendRouter, NotificationCollection, NotificationView, app) {
    "use strict";
    var HeaderView = Marionette.CompositeView.extend({

        className: 'navbar navbar-inverse navbar-fixed-top',

        template: headerTemplate,

        itemView: NotificationView,

        initialize: function () {
            _.bindAll(this);
            this.model = this.options.currentUser;
            this.collection = new NotificationCollection();
            this.collection.startPolling({add: true});
            this.collection.on('reset', this.setNotificationsNumber, this);
            this.collection.on('add', this.setNotificationsNumber, this);
            this.collection.on('remove', this.setNotificationsNumber, this);
            app.vent.on('closeNotification', this.removeModel);
        },

        events: {
            'click ul.notification-list': 'onClickNotification'
        },

        onClickNotification: function (event) {
            event.preventDefault();
            event.stopPropagation();
        },

        removeModel: function (model) {
            this.collection.remove(model);
        },

        onRender: function () {
            this.setNotificationsNumber();
        },

        setNotificationsNumber: function () {
            this.$el.find('.notification-number').text(this.collection.length);
            if (0 === this.collection.length) {
                this.$el.find('[data-tid="no-notifications"]').show();
            } else {
                this.$el.find('[data-tid="no-notifications"]').hide();
            }
        },

        serializeData: function () {
            return {
                user: this.model.toJSON(),
                logout: backendRouter.generate('fos_user_security_logout')
            };
        },

        appendHtml: function (collectionView, itemView) {
            collectionView.$el.find('ul.notification-list').append(itemView.el);
        }
    });

    return HeaderView;
});
