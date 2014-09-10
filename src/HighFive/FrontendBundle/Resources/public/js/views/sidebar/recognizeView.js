/*global define, setTimeout*/
define([
    'jquery',
    'underscore',
    'backbone',
    'syphon',
    'tpl!templates/sidebar/recognize.hbs',
    '../../app',
    '../../utils/backendController',
    '../../utils/backendForm',
    '../../utils/translator',
    'bootstrapTransition',
    'bootstrapButton',
    'chosen',
    'jqueryAutosize'
], function ($, _, Backbone, backboneSyphon, recognizeTemplate, app, backendController, backendForm, translator) {
    "use strict";
    var recognizeView = Backbone.View.extend({

        initialize: function () {
            _.bindAll(this);
        },

        events: {
            'click [data-tid="cancel"]': 'hide',
            'change [data-tid="points-range"]': 'updateNumberOfPoints',
            'submit form': 'recognize'
        },

        render: function () {
            var users = _.filter(app.userCollection.toJSON(), function (item) {
                return item.id !== app.currentUser.id;
            });

            this.$el.html(recognizeTemplate({
                users: users,
                max_points: app.currentUser.getMaxPoints()
            }));

            this.$el.find('textarea').autosize();

            setTimeout(this.applyPlugins, 0);

            return this;
        },

        applyPlugins: function () {
            this.$el.find('[data-tid="recipient_id"]').chosen({
                placeholder_text: translator.trans('recognition.placeholder.enter_name'),
                no_results_text: translator.trans('recognition.info.no_user_found')
            });
        },

        hide: function () {
            app.vent.trigger('hideModal', this);
        },

        updateNumberOfPoints: function (event) {
            this.$el.find('.form-comment-points-counter').text(event.currentTarget.value);
        },

        recognize: function (event) {
            event.preventDefault();

            var formElement = event.currentTarget,
                data = backboneSyphon.serialize(this);

            backendForm.removeValidationStates($(formElement));
            this.submitButtonElement = this.$el.find('[type="submit"]');

            if (formElement.checkValidity && !formElement.checkValidity()) {
                this.onFail();
            } else {
                this.submitButtonElement.button('loading');
                backendController.submitRecognition(data).done(this.onSuccess).fail(this.onFail).fail(this.onError);
            }

        },

        onSuccess: function () {
            app.vent.trigger('hideModal', this);
            app.vent.trigger('onRecognize');
            this.submitButtonElement.button('reset');
            app.vent.trigger('onGivePoints');
        },

        onFail: function () {
            this.submitButtonElement.button('reset');
        },

        onError: function (jqXHR) {
            if (jqXHR.status === 400) {
                backendForm.addValidationStates(this.$el, jqXHR);
            }
        }

    });

    return recognizeView;
});
