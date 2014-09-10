/*global define*/
define([
    'underscore',
    'jquery',
    'backbone',
    '../app',
    '../utils/backendController'
], function (_, $, Backbone, app, backendController) {
    "use strict";
    return Backbone.View.extend({

        initialize: function () {
            _.bindAll(this);
        },

        events: {
            'click [data-tid="cancel"]': 'cancel',
            'submit form': 'submit'
        },

        render: function () {
            this.renderContent(this.options.content);

            return this;
        },

        cancel: function () {
            app.vent.trigger('hideModal', this);
        },

        submit: function (event) {
            var form = event.currentTarget,
                url = form.action,
                data = $(form).serialize(),
                submitButtonElement = this.$el.find('[type="submit"]');

            event.preventDefault();
            if (form.checkValidity && !form.checkValidity()) {
                //TODO - Display notification when it will be available
                this.$el.find('[data-tid="message"]').focus();

                return;
            }
            submitButtonElement.button('loading');
            backendController.sendData(url, data).always(this.renderContent);
        },

        renderContent: function (content) {
            this.$el.html(content);
        }

    });
});
