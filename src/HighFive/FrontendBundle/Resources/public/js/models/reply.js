/*global define*/
define([
    'backbone',
    '../utils/backendRouter',
    './user',
    'relationalModel'
], function (Backbone, backendRouter, UserModel) {
    "use strict";

    return Backbone.RelationalModel.extend({

        urlRoot: backendRouter.generate('api_get_messages'),

        defaults: {
            points: 0
        },

        relations: [
            {
                type: Backbone.HasOne,
                key: 'recipient_user',
                keySource: 'recipient_id',
                keyDestination: 'recipient_user',
                relatedModel: UserModel
            },
            {
                type: Backbone.HasOne,
                key: 'sender_user',
                keySource: 'sender_id',
                keyDestination: 'sender_user',
                relatedModel: UserModel
            }
        ]

    });
});
