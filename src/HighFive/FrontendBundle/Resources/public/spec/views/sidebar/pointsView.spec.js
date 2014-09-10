/*global require, define, describe, beforeEach, it, expect, sinon*/
(function () {
    'use strict';
    define([
        'backbone',
        '../../../js/views/sidebar/pointsView',
        '../../../js/app',
        '../../../js/models/user',
        'jasmine',
        'sinon'
    ], function (Backbone, PointsView, app, UserModel) {
        describe('Points View Spec (js/views/sidebar/pointsView.js)', function () {
            beforeEach(function () {
                app.currentUser = new UserModel();
                this.view = new PointsView();
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
                            points: 10
                        });
                    });

                    it('it should change the point', function () {
                        var pointsView = this.view.$el.find('.points-content').text().match(/\d{1,}/)[0];
                        expect(parseInt(pointsView, 10)).toBe(app.currentUser.get('points'));
                    });

                });

                describe('Template', function () {
                    beforeEach(function () {
                        this.view.render();
                    });

                    it('produces the correct HTML', function () {
                        expect(this.view.$el).toContain('p.points-content');
                        expect(this.view.$el).toContain('[data-tid="recognize"]');
                    });
                });

                describe('When Recognize button handler fired', function () {
                    beforeEach(function () {
                        this.popupSpy = sinon.spy();
                        app.vent.on('showModal', this.popupSpy);
                        this.view.render();
                        this.view.$el.find('[data-tid="recognize"]').trigger('click');
                    });
                    it('shows the recognizeView', function () {
                        expect(this.popupSpy.callCount).toBe(1);
                    });
                });

            });

        });

    });
}());
