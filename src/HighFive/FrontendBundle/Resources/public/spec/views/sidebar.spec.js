/*global require, define, describe, beforeEach, it, expect*/
(function () {
    'use strict';
    define([
        '../../js/views/sidebar',
        'jasmine'
    ], function (SidebarView) {
        describe('Sidebar View (js/views/sidebar.js)', function () {
            beforeEach(function () {
                this.view = new SidebarView();
            });

            describe('Instantiation', function () {

                it('should create a div element', function () {
                    expect(this.view.el.nodeName).toEqual('DIV');
                });

                it("returns the expected regions", function () {
                    expect(this.view.profile).toBeDefined();
                    expect(this.view.points).toBeDefined();
                    expect(this.view.feedback).toBeDefined();
                    expect(this.view.board).toBeDefined();
                });

            });

        });

    });
}());
