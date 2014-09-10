/*global require, define, describe, beforeEach, it, expect, sinon, spyOn*/
(function () {
    'use strict';
    define([
        '../../../js/views/sidebar/feedbackView',
        'jquery',
        '../../../js/utils/backendController',
        'jasmine',
        'sinon'
    ], function (FeedbackView, $, backendController) {
        describe('Feedback View (js/views/sidebar/feedbackView.js)', function () {
            beforeEach(function () {
                this.view = new FeedbackView();
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
                        expect(this.view.$el).toContain('form.form-feedback');
                        expect(this.view.$el).toContain('form .form-feedback-textarea');
                        expect(this.view.$el).toContain('form .form-feedback-actions');
                    });
                });

                describe('When Submit button handler fired', function () {
                    beforeEach(function () {
                        spyOn(backendController, 'submitFeedback').andCallThrough();
                        this.view = new FeedbackView({
                            el: $('<div><form><input type="submit" value="Submit" /></form></div>')
                        });
                        this.view.$el.find('form').submit();
                    });
                    it('backendController.submitFeedback should be called', function () {
                        expect(backendController.submitFeedback).toHaveBeenCalled();
                    });
                });

            });

        });

    });
}());
