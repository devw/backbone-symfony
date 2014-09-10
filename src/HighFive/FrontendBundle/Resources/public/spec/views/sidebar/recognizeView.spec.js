/*global require, define, describe, beforeEach, it, expect, spyOn*/
(function () {
    'use strict';
    define([
        '../../../js/views/sidebar/recognizeView',
        '../../../js/utils/backendController',
        'jquery',
        'app',
        '../../../js/models/user',
        'jasmine',
        'sinon'
    ], function (RecognizeView, backendController, $, app, UserModel) {
        describe('Recognize View Spec (js/views/sidebar/recognizeView.js)', function () {
            beforeEach(function () {
                app.currentUser = new UserModel();
                this.view = new RecognizeView();
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

                describe('Template', function () {
                    beforeEach(function () {
                        this.view.render();
                    });

                    it('produces the correct HTML', function () {
                        expect(this.view.$el).toContain('div.modal-header [data-tid="cancel"]');
                        expect(this.view.$el).toContain('form.form-modal [data-tid="user-id"]');
                        expect(this.view.$el).toContain('form.form-modal [data-tid="name"]');
                    });
                });

                describe('When HighFive button handler fired', function () {
                    beforeEach(function () {
                        spyOn(backendController, 'submitRecognition').andCallThrough();
                        this.view = new RecognizeView({
                            el: $('<div><form><input type="submit" value="Submit" /></form></div>')
                        });
                        this.view.$el.find('form').submit();
                    });
                    it('backendController.submitRecognition should be called', function () {
                        expect(backendController.submitRecognition).toHaveBeenCalled();
                    });
                });

            });

        });

    });
}());
