/*global define, describe, beforeEach, it, expect, afterEach, sinon */
define([
    '../../js/models/user',
    '../../js/collections/users',
    '../../js/utils/backendRouter',
    '../fixtures/users.fixture',
    'sinon'
], function (User, UserCollection, backendRouter) {
    'use strict';
    describe('User model', function () {
        beforeEach(function () {
            this.model = new User();
            this.collection = new UserCollection();
        });

        describe('When creating models', function () {
            it('the url should be equal at corresponding api_get_users value', function () {
                expect(this.model.url()).toEqual(backendRouter.generate('api_get_users'));
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
                this.fixture = this.fixtures.Users.valid;
                this.fixtureResponse = this.fixture.response.users[0];
                this.server = sinon.fakeServer.create(1);
                this.server.respondWith(
                    'GET',
                    backendRouter.generate('api_get_users') + '/' + this.model.get('id'),
                    JSON.stringify(this.fixtureResponse)
                );
            });

            afterEach(function () {
                this.server.restore();
            });

            it('should make the correct request', function () {
                this.model.fetch();
                expect(this.server.requests.length).toEqual(1);
                expect(this.server.requests[0].method).toEqual('GET');
                expect(this.server.requests[0].url).toEqual(this.model.url());
            });

            it('should the response not change', function () {
                this.model.fetch();
                this.server.respond();
                expect(this.fixtureResponse).toEqual(this.model.attributes);
            });

            it('should exhibit mandatory attributes', function () {
                expect(this.model.get('id')).toBeGreaterThan(0);
            });
        });

    });

});
