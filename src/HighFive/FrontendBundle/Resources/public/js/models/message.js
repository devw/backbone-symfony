/*global define*/
define([
    'backbone',
    '../utils/backendRouter',
    './reply',
    './user',
    '../collections/messagesReply',
    'relationalModel'
], function (Backbone, backendRouter, ReplyModel, UserModel, MessagesReplyCollection) {
    "use strict";

    return Backbone.RelationalModel.extend({

        defaults: {
            points: 0
        },

        urlRoot: backendRouter.generate("api_get_messages"),

        relations: [
            {
                type: Backbone.HasMany,
                key: 'replies',
                relatedModel: ReplyModel,
                collectionType: MessagesReplyCollection,
                reverseRelation: {
                    key: 'parent',
                    includeInJSON: Backbone.Model.prototype.idAttribute
                }
            },
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
