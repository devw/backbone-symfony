/*global define*/
define([
    'backbone',
    '../models/reply',
    '../utils/backendRouter'
], function (Backbone, ReplyModel, backendRouter) {
    'use strict';

    return Backbone.Collection.extend({

        model: ReplyModel,

        url: function () {
            return backendRouter.generate('api_get_message_replies', {id: this.messageId});
        }

    });
});
