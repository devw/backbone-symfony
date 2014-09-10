/*global define*/
define([
    'underscore',
    '../htmlView'
], function (_, HtmlView) {
    "use strict";
    return HtmlView.extend({

        events: _.extend({}, HtmlView.prototype.events, {
            'click [data-tid="add"]': 'addRow'
        }),

        addRow: function (e) {
            e.preventDefault();
            var coll = this.$el.find('[data-tid="emails"]');
            var count = coll.children('.control-group').length;
            var proto = coll.attr('data-prototype').replace(/__name__label__/g, count).replace(/__name__/g, count);
            coll.append(proto);
        }

    });
});
