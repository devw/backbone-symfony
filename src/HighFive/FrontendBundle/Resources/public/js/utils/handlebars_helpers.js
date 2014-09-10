/*global define*/
define([
    'handlebars',
    './translator'
], function (Handlebars, translator) {
    'use strict';

    /**
     * Helper to translate simple strings.
     *
     * Usage, with their equivalents in the Symfony2 translator
     *
     * {{ trans "foo.bar" }}                                ->trans("foo.bar")
     * {{ trans "foo.bar" name="foo" email=user.email }}    ->trans("foo.bar", array('%name%' => 'foo', '%email%' => $user['email']))
     * {{ trans "foo.bar" "validators" value="test" }}      ->trans("foo.bar", array('%value%' => 'test'), 'validators')
     */
    Handlebars.registerHelper('trans', function (message, domain, options) {
        if ('undefined' ===  typeof options && 'string' !== typeof domain) {
            options = domain;
            domain = undefined;
        }
        return translator.trans(message, options.hash || {}, domain);
    });

    /**
     * Helper to translate pluralized strings.
     *
     * Usage, with their equivalents in the Symfony2 translator
     *
     * {{ transChoice "foo.bar" count }}                                ->transChoice("foo.bar", $count)
     * {{ transChoice "foo.bar" count name="foo" email=user.email }}    ->transChoice("foo.bar", $count, array('%name%' => 'foo', '%email%' => $user['email']))
     * {{ transChoice "foo.bar" count "validators" value="test" }}      ->transChoice("foo.bar", $count, array('%value%' => 'test'), 'validators')
     */
    Handlebars.registerHelper('transChoice', function (message, number, domain, options) {
        if ('undefined' ===  typeof options && 'string' !== typeof domain) {
            options = domain;
            domain = undefined;
        }
        return translator.transChoice(message, number, options.hash || {}, domain);
    });

    Handlebars.registerHelper('nl2br', function (value) {
        return new Handlebars.SafeString(
            Handlebars.Utils.escapeExpression(value).replace(/(\r?\n)/g, '<br>$1')
        );
    });
});
