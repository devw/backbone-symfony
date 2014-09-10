/*global define*/
define([
    'marionette',
    'tpl!templates/sidebar/usersBoard.hbs',
    './userBoardItemView',
    '../../collections/usersBoard',
    '../../app'
], function (Marionette, usersBoardTemplate, UserBoardItemView, UserBoardCollection, app) {
    "use strict";
    return Marionette.CompositeView.extend({

        className: 'widget widget-board',

        template: usersBoardTemplate,

        itemView: UserBoardItemView,

        initialize: function () {
            this.collection = new UserBoardCollection();
            this.collection.startPolling({
                minutes: 10,
                add: false
            });
            app.vent.on('onGivePoints', this.collection.restartPolling);
        },

        appendHtml: function (collectionView, itemView) {
            collectionView.$el.find('ol.list-board').append(itemView.el);
        },

        onRender: function () {
            this.collection.length > 0 ? this.$el.show() : this.$el.hide();
        }

    });
});
