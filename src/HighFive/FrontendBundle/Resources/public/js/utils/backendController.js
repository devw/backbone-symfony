/*global define*/
define([
    'jquery',
    '../app',
    './backendRouter'
], function ($, app, backendRouter) {
    "use strict";

    return {
        /**
         * Submits some feedback to the backend.
         * @param {String} message
         * @return {jQuery.Deferred.promise}
         */
        submitFeedback: function (message) {
            return $.ajax({
                url: backendRouter.generate('feedback_send'),
                type: 'POST',
                dataType: 'json',
                data: {
                    message: message
                }
            }).done(function () {
                app.vent.trigger('track', 'Send feedback');
            });
        },

        getInvitationView: function () {
            return $.get(backendRouter.generate('invitation_invite'));
        },

        getInvitationWithoutCoworkersView: function () {
            return $.get(backendRouter.generate('invitation_no_coworkers'));
        },

        sendData: function (url, data) {
            return $.ajax({
                url: url,
                type: 'POST',
                data: data
            });
        },

        submitRecognition: function (data) {
            return $.ajax({
                url: backendRouter.generate('api_post_recognitions'),
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify(data),
                contentType: 'application/json'
            }).done(function () {
                app.vent.trigger('track', 'Recognize someone');
            });
        },

        submitComment: function (data, messageId) {
            return $.ajax({
                url: backendRouter.generate('api_post_message_replies', {id: messageId}),
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify(data),
                contentType: 'application/json'
            }).done(function () {
                app.vent.trigger('track', 'Send reply');
            });
        },

        closeNotification: function (data) {
            // The beforeSend allow us to get rid of the IE8 bug which seems to not support PATCH requests.
            // TODO - Find a way to use a real patch request for modern browsers
            var ajax = $.ajax({
                url: backendRouter.generate('api_patch_notification', {id: data.get('id')}),
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-HTTP-METHOD-OVERRIDE', 'PATCH');
                },
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify(data),
                contentType: 'application/json'
            });

            return ajax;
        }

    };
});
