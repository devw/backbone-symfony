/*global define*/
define([
    'underscore',
    'jquery'
], function (_, $) {
    'use strict';

    return {
        addValidationStates: function (element, jqXHR) {
            (function parser(object, name) {
                var divElement,
                    ul,
                    key;

                if (object.errors) {
                    if (name === 'rootNode') {
                        divElement = $('<div>').addClass('form-error');
                        element.find('form').prepend(divElement);
                        ul = $('<ul>');
                        _.each(object.errors, function (error) {
                            ul.append($('<li>').text(error));
                        });
                        divElement.append(ul);
                    } else {
                        divElement = element.find('[name="' + name + '"]');
                        divElement.closest('.control-group').addClass('error');
                        _.each(object.errors, function (error) {
                            divElement.closest('.controls')
                                .append($('<span>').text(error).addClass('help-block error-message'));
                        });
                    }
                }
                if (object.hasOwnProperty('children')) {
                    for (key in object.children) {
                        if (object.children.hasOwnProperty(key)) {
                            parser(object.children[key], key);
                        }
                    }
                }
            }(JSON.parse(jqXHR.responseText, 'rootNode')));
        },

        removeValidationStates: function (element) {
            element.find('.control-group').removeClass('error');
            element.find('.help-block.error-message').remove();
        }
    };

});
