/*global require, define, describe, beforeEach, it, expect*/
(function () {
    'use strict';
    define([
        '../../js/views/header',
        '../../js/utils/backendRouter',
        'backbone',
        'jasmine'
    ], function (HeaderView, backendRouter, Backbone) {
        describe('Header View Spec - (js/views/header.js)', function () {
            beforeEach(function () {
                this.view = new HeaderView({
                    currentUser: new Backbone.Model()
                });
                this.view.render();
            });

            describe('Instantiation', function () {

                it('should create a div element', function () {
                    expect(this.view.el.nodeName).toEqual('DIV');
                });

            });

            describe("Rendering", function () {

                describe('Changing the model:', function () {
                    beforeEach(function () {
                        this.view.model.set({
                            first_name: 'jasmine',
                            last_name: 'test'
                        });
                        this.view.render();
                    });

                    it('it should change the name', function () {
                        var viewName = this.view.$el.find('.user-info span').text().replace(/[\s\-]/g, ''),
                            newName = this.view.model.get('first_name') + this.view.model.get('last_name');
                        expect(viewName).toBe(newName);
                    });

                });

                describe('Template', function () {
                    beforeEach(function () {
                        this.view.render();
                    });

                    it("produces the correct HTML", function () {
                        expect(this.view.$el).toContain('div.navbar-inner > div.container > a.brand');
                        expect(this.view.$el).toContain('div.navbar-inner > div.container > div.pull-right');
                        expect(this.view.$el).toContain('div.pull-right > ul.nav > li.dropdown');
                    });

                    it("has the correct URL", function () {
                        var logoutItem = _.find(this.view.$el.find('.dropdown-menu.menu-user li'),function (obj) {
                            return $(obj).find('a').attr('href') === backendRouter.generate('fos_user_security_logout');
                        });
                        expect(logoutItem).toBeDefined();
                    });

                });

            });

        });

    });
}());
