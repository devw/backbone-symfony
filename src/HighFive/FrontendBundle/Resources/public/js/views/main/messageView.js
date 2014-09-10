/*global define*/
define([
    'require',
    'jquery',
    'underscore',
    'marionette',
    'tpl!templates/main/message.hbs',
    '../../views/main/reply',
    '../../app',
    '../../utils/dateFormatter'
], function (require, $, _, Marionette, messageTemplate, ReplyView, app, dateFormatter) {

    "use strict";
    return Marionette.CompositeView.extend({

        tagName: 'li',

        className: 'message',

        itemView: ReplyView,

        template: messageTemplate,

        events: {
            'click  [data-tid="show-comment"]': 'showComment',
            'click  [data-tid="add-points"]': 'showPointsField',
            'change [data-tid="points-range"]': 'updateNumberOfPoints',
            'submit form.form-comment': 'comment'
        },

        initialize: function () {
            _.bindAll(this, 'onSuccess', 'onError', 'onFail', 'doComment');
            this.collection = this.model.get('replies');
        },

        appendHtml: function (collectionView, itemView) {
            collectionView.$el.find('ul.list.list-replies').append(itemView.el);
        },

        serializeData: function () {
            var modelJSON = this.model.toJSON(),
                maxPoints = app.currentUser.getMaxPoints(),
                sender_user = modelJSON.sender_user,
                recipient_user = modelJSON.recipient_user;

            modelJSON.created_date_human_readable = dateFormatter.formatHumanReadableDate(modelJSON.created_at);
            this.can_give_points = recipient_user.id !== app.currentUser.id && maxPoints > 0;

            return {
                recipient_is_sender: sender_user && recipient_user.id === sender_user.id,
                recipient_is_current_user: recipient_user.id === app.currentUser.id,
                can_give_points: this.can_give_points,
                message: modelJSON,
                has_recognition: "undefined" !== typeof modelJSON.recognition,
                has_replies: 0 < modelJSON.replies.length,
                max_points: maxPoints,
                sender_name: sender_user ? sender_user.first_name + ' ' + sender_user.last_name : null,
                recipient_name: recipient_user.first_name + ' ' + recipient_user.last_name
            };
        },

        onRender: function () {
            this.repliesContainerElement = this.$el.find('.message-replies');
            this.formElement = this.$el.find('.form-comment');
            this.textAreaElement = this.formElement.find('textarea');
            this.pointsFieldElement = this.formElement.find('.form-comment-points');
            this.pointsCounter = this.formElement.find('.form-comment-points-counter');
        },

        showComment: function () {
            this.formElement.removeClass('hide');
            this.formElement[0].reset();
            this.textAreaElement.focus();
        },

        showPointsField: function (event) {
            this.pointsFieldElement.removeClass('hide');
            $(event.currentTarget).addClass('hide');
        },

        updateNumberOfPoints: function (event) {
            this.pointsCounter.text(event.currentTarget.value);
        },

        comment: function (event) {
            event.preventDefault();

            require(['syphon', '../../utils/backendController', '../../utils/backendForm'], this.doComment);
        },

        doComment: function (backboneSyphon, backendController, backendForm) {
            var element = this.formElement[0],
                data = backboneSyphon.serialize(this);

            this.submitButtonElement = this.$el.find('[type="submit"]');
            backendForm.removeValidationStates(this.formElement);

            if (element.checkValidity && !element.checkValidity()) {
                this.onFail();
            } else {
                this.submitButtonElement.button('loading');
                backendController.submitComment(data, this.model.get('id')).done(this.onSuccess).fail(this.onError);
            }
        },

        onSuccess: function (data) {
            this.resetSlider();
            this.collection.messageId = this.model.get('id');
            this.collection.fetch();
            if (this.repliesContainerElement.hasClass('hide')) {
                this.repliesContainerElement.removeClass('hide');
            }
            this.formElement.addClass('hide');
            this.submitButtonElement.button('reset');
            if (data.recognition && data.recognition.points > 0) {
                app.vent.trigger('onGivePoints');
            }
        },

        onFail: function () {
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

        resetSlider: function () {
            this.$el.find('[data-tid="points-range"]').val(0).change();
        }

    });
});
