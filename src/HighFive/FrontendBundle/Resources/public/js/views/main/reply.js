/*global define*/
define([
    'marionette',
    'tpl!templates/main/reply.hbs',
    '../../app',
    '../../utils/dateFormatter'
], function (Marionette, replyTemplate, app, dateFormatter) {
    "use strict";
    return Marionette.ItemView.extend({

        template: replyTemplate,

        tagName: 'li',

        serializeData: function () {
            var modelJSON = this.model.toJSON();

            modelJSON.created_date_human_readable = dateFormatter.formatHumanReadableDate(modelJSON.created_at);

            return {
                reply: modelJSON,
                is_own_reply: app.currentUser.id === modelJSON.sender_user.id,
                has_recognition: "undefined" !== typeof modelJSON.recognition
            };
        }

    });
});
