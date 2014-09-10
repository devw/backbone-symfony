/*global define*/
define([
    'backbone',
    '../utils/backendRouter',
    'relationalModel'
], function (Backbone, backendRouter) {
    "use strict";

    var User = Backbone.RelationalModel.extend({

        defaults: {
            points: 0
        },

        urlRoot: backendRouter.generate("api_get_users"),

        getMaxPoints: function () {
            var remainingPoints = this.get('remaining_points'),
                MAX_POINTS = 50;

            return remainingPoints > MAX_POINTS ? MAX_POINTS : remainingPoints;
        }

    });

    return User;
});
