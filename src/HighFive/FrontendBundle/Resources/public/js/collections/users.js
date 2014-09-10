/*global define*/
define([
    'backbone',
    '../models/user',
    '../utils/backendRouter'
], function (Backbone, User, backendRouter) {
    'use strict';

    var UserCollection = Backbone.Collection.extend({

        model: User,

        url: backendRouter.generate('api_get_users')

    });

    return UserCollection;
});
