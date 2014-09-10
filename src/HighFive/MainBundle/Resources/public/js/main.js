/*global jQuery, window */
jQuery(function ($) {
    $('form').on('submit', function (event) {
        $(event.currentTarget).find('[data-tid="loading-button"]').button('loading');
    });
});
