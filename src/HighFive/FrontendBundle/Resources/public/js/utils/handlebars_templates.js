/*global define*/
define([
    'handlebars',
    'utils/handlebars_helpers'
], function (Handlebars) {
    'use strict';
    var loadResource = function (resourceName, parentRequire, callback, config) {
        parentRequire([("text!" + resourceName)],
            function (templateContent) {
                var template = Handlebars.compile(templateContent);
                callback(template);
            }
        );
    };

    return {
        load: loadResource
    };
});
