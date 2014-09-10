/*global require, define, describe, beforeEach, it, expect, sinon, spyOn*/
(function () {
    'use strict';
    define([
        '../../../js/views/header/notificationView',
        'jasmine',
        'sinon',
        '../../fixtures/notifications.fixture'
    ], function (NotificationView) {
        describe('Notification View', function () {
            beforeEach(function () {
                this.fixture = this.fixtures.Notifications.valid;
                this.fixtureResponse = this.fixture.response.notifications[0];
                this.view = new NotificationView({
                    model: new Backbone.Model(this.fixture.response.notifications[0])
                });
            });

            describe('Instantiation', function () {

                it('should create a div element', function () {
                    expect(this.view.el.nodeName).toEqual('LI');
                });
            });

            describe('Rendering', function () {

                beforeEach(function () {
                    this.view.render();
                });

                describe('Template', function () {
                    it('produces the correct HTML', function () {
                        expect(this.view.$el).toContain('[data-tid="read"]');
                        expect(this.view.$el).toContain('span.notification-message');
                        expect(this.view.$el).toContain('span.notification-read');
                    });
                });

                describe('When read button handler fired', function () {
                    beforeEach(function () {
                        spyOn(NotificationView.prototype, 'closeNotification');
                        this.view = new NotificationView({
                            model: new Backbone.Model(this.fixture.response.notifications[0])
                        });
                        this.view.render();
                        this.view.$el.find('[data-tid="read"]').click();
                    });
                    it('Notification View should be close Notification', function () {
                        expect(NotificationView.prototype.closeNotification).toHaveBeenCalled();
                    });
                });

            });

        });

    });
}());
