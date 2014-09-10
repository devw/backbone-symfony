/*global define*/
define([
    'underscore',
    'backbone',
    '../models/message',
    '../utils/backendRouter',
    'moment',
    '../utils/poller'
], function (_, Backbone, Message, backendRouter, moment, poller) {
    'use strict';

    var MessageCollection = Backbone.Collection.extend({

        model: Message,

        url: backendRouter.generate('api_get_messages'),

        comparator: function (model) {
            return -(moment(model.get('created_at')).unix());
        }

    });

    _.extend(MessageCollection.prototype, poller);

    return MessageCollection;

});
