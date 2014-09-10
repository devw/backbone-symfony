/*global define*/
define([
    'marionette',
    'tpl!templates/sidebar/userBoardItem.hbs',
    '../../app'
], function (Marionette, userBoardItemTemplate, app) {
    "use strict";
    return Marionette.ItemView.extend({

        className: 'list-board-user',

        template: userBoardItemTemplate,

        tagName: 'li',

        serializeData: function () {
            var modelJSON = this.model.toJSON();

            return {
                user: modelJSON,
                is_own_ranking: app.currentUser.id === modelJSON.id
            };
        }

    });
});
