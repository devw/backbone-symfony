/*global define, describe, beforeEach, it, expect, afterEach, sinon */
define([
    '../../js/models/notification',
    '../../js/collections/notifications',
    '../../js/utils/backendRouter',
    '../fixtures/notifications.fixture',
    'sinon'
], function (NotificationModel, NotificationCollection, backendRouter) {
    'use strict';
    describe('Notification model', function () {
        beforeEach(function () {
            this.model = new NotificationModel();
            this.collection = new NotificationCollection();
        });

        describe('When creating collection', function () {
            it('the url should be equal at corresponding api_get_notifications value', function () {
                expect(this.collection.url).toEqual(backendRouter.generate('api_get_notifications'));
            });

        });

        describe('when fetching model from server', function () {
            beforeEach(function () {
                this.model.set({id: 1});
                this.fixture = this.fixtures.Notifications.valid;
                this.fixtureResponse = this.fixture.response.notifications[0];
                this.server = sinon.fakeServer.create(1);
                this.server.respondWith(
                    'GET',
                    backendRouter.generate('api_get_notifications') + '/' + this.model.get('id'),
                    JSON.stringify(this.fixtureResponse)
                );
            });

            afterEach(function () {
                this.server.restore();
            });

            it('should make the correct request', function () {
                this.collection.fetch();
                expect(this.server.requests.length).toEqual(1);
                expect(this.server.requests[0].method).toEqual('GET');
                expect(this.server.requests[0].url).toEqual(this.collection.url);
            });
        });

    });

});
