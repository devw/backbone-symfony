/*global define*/
define([
    'underscore',
    'backbone',
    '../models/notification',
    '../utils/backendRouter',
    '../utils/poller'
], function (_, Backbone, NotificationModel, backendRouter, poller) {
    'use strict';

    var NotificationCollection = Backbone.Collection.extend({

        model: NotificationModel,

        url: backendRouter.generate('api_get_notifications')

    });

    _.extend(NotificationCollection.prototype, poller);

    return NotificationCollection;
});
