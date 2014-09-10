/*global define*/
define([
    'backbone',
    '../utils/backendRouter',
    '../utils/poller'
], function (Backbone, backendRouter, poller) {
    'use strict';

    var UsersBoardCollection = Backbone.Collection.extend({

        url: function () {
            return backendRouter.generate("api_get_users_monthly_board");
        }

    });

    _.extend(UsersBoardCollection.prototype, poller);

    return UsersBoardCollection;

});
