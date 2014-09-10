/*global require, define, describe, beforeEach, it, expect, sinon*/
(function () {
    'use strict';
    define([
        '../../../js/views/main/wallView',
        '../../../js/app',
        '../../fixtures/users.fixture',
        'jasmine',
        'sinon'
    ], function (WallView, app) {
        describe('Wall View', function () {
            beforeEach(function () {
                this.fixture = this.fixtures.Users.valid;
                app.userCollection = new Backbone.Collection(this.fixture.response.users);
                app.currentUser = app.userCollection.at(0);

                this.view = new WallView();
            });

            describe('Instantiation', function () {
                it('should create a div element', function () {
                    expect(this.view.el.nodeName).toEqual('DIV');
                });

            });

            describe('Rendering', function () {

                describe('Template', function () {
                    beforeEach(function () {
                        this.view.render();
                    });

                    it('produces the correct HTML', function () {
                        expect(this.view.$el).toContain('form [data-tid="announcement"]');
                        expect(this.view.$el).toContain('ul.list-messages');
                    });
                });

            });

            describe('When Post Update button handler fired', function () {
                beforeEach(function () {
                    spyOn(WallView.prototype, 'submitForm').andCallThrough();
                    this.view = new WallView({
                        el: $('<div><form class="form form-announcement"><input type="submit" /></form></div>')
                    });
                    this.view.$el.find('form').submit();
                });
                it('submitForm should be called', function () {
                    expect(WallView.prototype.submitForm).toHaveBeenCalled();
                });
            });

        });

    });
}());
