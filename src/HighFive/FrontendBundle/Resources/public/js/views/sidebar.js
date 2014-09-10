/*global define*/
define([
    'marionette',
    'tpl!templates/sidebar/main.hbs',
    './sidebar/profileView',
    './sidebar/pointsView',
    './sidebar/feedbackView',
    './sidebar/usersBoardView',
    './sidebar/linksView'
], function (Marionette, sidebarTemplate, ProfileView, PointsView, FeedbackView, BoardView, LinksView) {
    "use strict";
    var SidebarView = Marionette.Layout.extend({

        initialize: function () {
            this.on('render', function () {
                this.profile.show(new ProfileView());
                this.points.show(new PointsView());
                this.board.show(new BoardView());
                this.feedback.show(new FeedbackView());
                this.links.show(new LinksView());
            });
        },

        template: sidebarTemplate,

        regions: {
            profile: '#profile',
            points: '#points',
            board: '#board',
            feedback: '#feedback',
            links: '#links'
        }

    });

    return SidebarView;
});
