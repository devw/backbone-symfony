/*global require, define, describe, beforeEach, it, expect, sinon*/
(function () {
    'use strict';
    define([
        'backbone',
        '../../../js/views/sidebar/profileView',
        '../../../js/app',
        '../../../js/models/user',
        'jasmine',
        'sinon'
    ], function (Backbone, ProfileView, app, UserModel) {
        describe('Profile View Spec (js/views/sidebar/profileView.js)', function () {
            beforeEach(function () {
                app.currentUser = new UserModel({
                    remaining_points: 1
                });
                this.view = new ProfileView();
            });

            describe('Instantiation', function () {

                it('should create a div element', function () {
                    expect(this.view.el.nodeName).toEqual('DIV');
                });

            });

            describe('Rendering', function () {

                it('returns the view object', function () {
                    expect(this.view.render()).toEqual(this.view);
                });

                describe('Changing the model:', function () {
                    beforeEach(function () {
                        app.currentUser.set({
                            remaining_points: 0
                        });
                    });

                    it('it should change the point', function () {
                        var pointsView = this.view.$el.find('.block-profile-remaining-points').text().match(/\d{1,}/)[0];
                        expect(parseInt(pointsView, 0)).toBe(app.currentUser.get('points'));
                    });

                });

                describe('Template', function () {
                    beforeEach(function () {
                        this.view.render();
                    });

                    it('produces the correct HTML', function () {
                        expect(this.view.$el).toContain('figure.avatar');
                        expect(this.view.$el).toContain('.avatar-caption-title');
                        expect(this.view.$el).toContain('.block-profile-remaining-points');
                    });
                });

            });

        });

    });
}());
