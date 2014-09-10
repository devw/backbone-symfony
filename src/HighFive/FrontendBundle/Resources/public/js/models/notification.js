/*global define*/
define([
    'backbone',
    './user',
    'relationalModel'
], function (Backbone, UserModel) {
    "use strict";

    return Backbone.RelationalModel.extend({

        relations: [
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
