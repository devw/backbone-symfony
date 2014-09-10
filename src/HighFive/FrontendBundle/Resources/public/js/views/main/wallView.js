/*global define*/
define([
    'require',
    'jquery',
    'underscore',
    'marionette',
    'tpl!templates/main/wall.hbs',
    './messageView',
    '../../collections/messages',
    '../../models/message',
    '../../app',
    'bootstrapButton',
    'jqueryAutosize'
], function (require, $, _, Marionette, wallTemplate, messageView, MessageCollection, MessageModel, app) {
    "use strict";
    return Marionette.CompositeView.extend({

        template: wallTemplate,

        itemView: messageView,

        collection: new MessageCollection(),

        initialize: function () {
            _.bindAll(this, 'onError', 'onFail', 'onSuccess', 'updateMaxPointsRange', 'doSubmitForm');
            this.collection.startPolling({add: true});
            app.vent.on('onRecognize', this.collection.restartPolling);
            app.vent.on('onGivePoints', this.updateMaxPointsRange);
        },

        events: {
            'submit form.form-announcement': 'submitForm',
            'focus  [data-tid="announcement"]': 'focusForm',
            'blur   [data-tid="announcement"]': 'blurForm'
        },

        focusedClass: 'is-form-focused',

        focusForm: function () {
            this.$el.find('form').addClass(this.focusedClass);
            this.$el.find('.form-announcement-actions').removeClass('hide');
            this.$el.find('textarea').autosize();
        },

        blurForm: function () {
            if (!this.$el.find('[data-tid="announcement"]').val().length) {
                this.$el.find('form').removeClass(this.focusedClass);
                this.$el.find('.form-announcement-actions').addClass('hide');
                /* it removes the style attribute set by autosize plugin */
                this.$el.find('textarea').removeAttr('style');
            }
        },

        appendHtml: function (collectionView, itemView, index) {
            var childrenContainer = collectionView.$el.find('ul.list-messages'),
                children = childrenContainer.children();

            if (children.size() === index) {
                childrenContainer.append(itemView.el);
            } else {
                children.eq(index).before(itemView.el);
            }

        },

        submitForm: function (event) {
            event.preventDefault();

            require(['../../utils/backendForm'], this.doSubmitForm);
        },

        doSubmitForm: function (backendForm) {
            var form = this.$el.find('form'),
                element = form[0];

            backendForm.removeValidationStates(form);

            this.textAreaElement = this.$el.find('[data-tid="announcement"]');
            this.submitButtonElement = this.$el.find('[type="submit"]');

            if (element.checkValidity && !element.checkValidity()) {
                this.onFail();
            } else {
                this.submitButtonElement.button('loading');
                this.messageModel = new MessageModel();
                this.jqXHR = this.messageModel.save({
                    message: this.textAreaElement.val()
                }, {
                    wait: true,
                    success: this.onSuccess,
                    error: this.onError
                });
            }
        },

        onFail: function () {
            this.textAreaElement.focus();
            this.submitButtonElement.button('reset');
        },

        onError: function () {
            this.onFail();
            if (this.jqXHR.status === 400) {
                require(['../../utils/backendForm'], _.bind(function (backendForm) {
                    backendForm.addValidationStates(this.$el, this.jqXHR);
                }, this));
            }
        },

        onSuccess: function () {
            app.vent.trigger('track', 'Send announcement');
            this.collection.add(this.messageModel);
            this.textAreaElement.val('');
            this.submitButtonElement.button('reset');
            this.blurForm();
        },

        /**
         * This will update the max attributes for all points-range elements in the wall composite view.
         **/
        updateMaxPointsRange: function () {
            var pointsRangeElements = this.$el.find('[data-tid="points-range"]');
            app.currentUser.fetch({
                success: function () {
                    pointsRangeElements.attr('max', app.currentUser.getMaxPoints());
                }
            });
        }

    });
});
