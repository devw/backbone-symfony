/*global define*/
define([
    'require',
    'jquery',
    'underscore',
    'backbone',
    '../../app',
    'tpl!templates/sidebar/feedback.hbs',
    'bootstrapButton'
], function (require, $, _, Backbone, app, feedbackTemplate) {
    "use strict";
    return Backbone.View.extend({

        className: 'widget widget-feedback',

        initialize: function () {
            _.bindAll(this, 'onSuccess', 'onFail', 'onError', 'doSubmitForm');
        },

        events: {
            'focus  [data-tid="message"]': 'focusForm',
            'blur   [data-tid="message"]': 'blurForm',
            'submit form': 'submitForm'
        },

        focusedClass: 'is-form-focused',

        render: function () {
            this.$el.html(feedbackTemplate());

            return this;
        },

        focusForm: function () {
            this.formElement = this.$el.find('form');
            this.messageElement = this.$el.find('[data-tid="message"]');
            this.submitButtonElement = this.$el.find('[type="submit"]');

            this.formElement.addClass(this.focusedClass);
        },

        blurForm: function () {
            if (!this.messageElement.val().length) {
                this.formElement.removeClass(this.focusedClass);
            }
        },

        submitForm: function (event) {
            event.preventDefault();

            require(['../../utils/backendController', '../../utils/backendForm'], this.doSubmitForm);
        },

        doSubmitForm: function (backendController, backendForm) {
            var formElement = this.formElement[0],
                message = this.messageElement;

            backendForm.removeValidationStates(this.formElement);

            if (formElement.checkValidity && !formElement.checkValidity()) {
                this.onFail();
            } else {
                this.submitButtonElement.button('loading');
                backendController.submitFeedback(message.val()).done(this.onSuccess).fail(this.onError);
            }
        },

        onFail: function () {
            this.messageElement.focus();
            this.submitButtonElement.button('reset');
        },

        onError: function (jqXHR) {
            this.onFail();
            if (jqXHR.status === 400) {
                require(['../../utils/backendForm'], _.bind(function (backendForm) {
                    backendForm.addValidationStates(this.$el, jqXHR);
                }, this));
            }
        },

        onSuccess: function () {
            this.messageElement.val('');
            this.formElement.removeClass(this.focusedClass);
            this.submitButtonElement.button('reset');

            require(['humane', '../../utils/translator'], function (humane, translator) {
                humane.log(translator.trans('feedback.message.sent'));
            });
        }
    });
});
