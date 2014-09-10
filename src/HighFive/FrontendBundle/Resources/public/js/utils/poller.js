/*global define, setTimeout*/
define([
    'underscore'
], function (_) {
    "use strict";

    return {

        executePolling: function () {
            this.fetch({
                success: this.onFetch,
                add: this.options.add
            });
        },

        startPolling: function (options) {
            _.bindAll(this, 'onFetch', 'executePolling', 'restartPolling');
            this.options = options || {};
            this.minutes = this.options.minutes || 2;
            this.polling = true;
            this.executePolling();
        },

        stopPolling: function () {
            this.polling = false;
        },

        onFetch: function () {
            if (this.polling) {
                setTimeout(this.executePolling, 1000 * 60 * this.minutes);
            }
        },

        restartPolling: function () {
            var options = this.options;
            this.stopPolling();
            this.startPolling(options);
        }
    };

});
