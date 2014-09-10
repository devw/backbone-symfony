/*global define, describe, beforeEach, it, expect, afterEach, sinon */
define([
    '../../js/models/message',
    '../../js/collections/messages',
    '../../js/utils/backendRouter',
    '../fixtures/messages.fixture',
    'sinon'
], function (Message, MessageCollection, backendRouter) {
    'use strict';
    describe('Message Model', function () {
        beforeEach(function () {
            this.model = new Message();
            this.collection = new MessageCollection();
        });

        describe('When creating models', function () {
            it('the url should be equal at corresponding api_get_messages value', function () {
                expect(this.model.url()).toEqual(backendRouter.generate('api_get_messages'));
            });

            describe('when no id is set', function () {
                it('should return the collection URL', function () {
                    expect(this.model.url()).toEqual(this.collection.url);
                });

            });
            describe('when id is set', function () {
                it('should return the collection URL and id', function () {
                    this.model.set({id: 1});
                    expect(this.model.url()).toEqual(this.collection.url + '/' + this.model.get('id'));
                });
            });

        });

        describe('when fetching model from server', function () {
            beforeEach(function () {
                this.model.set({id: 1});
                this.fixture = this.fixtures.Messages.valid;
                this.fixtureResponse = this.fixture.response.messages[0];
                this.server = sinon.fakeServer.create(1);
                this.server.respondWith(
                    'GET',
                    backendRouter.generate('api_get_messages') + '/' + this.model.get('id'),
                    JSON.stringify(this.fixtureResponse)
                );
            });

            afterEach(function () {
                this.server.restore();
            });

            it('should exhibit mandatory attributes', function () {
                expect(this.model.get('id')).toBeGreaterThan(0);
                expect(this.model.get('points')).toBeDefined();
            });
        });

    });

});
