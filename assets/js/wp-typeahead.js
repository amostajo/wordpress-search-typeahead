/**
 * Search Typeahead Addon init file.
 * For Wordpress Development Templates
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @version 1.0.0
 */
(function ($) {

    /**
     * Init typeahead.
     * @since 1.0
     */
    $('#search-typeahead').each(function () {
        // Typeahead
        $(this).typeahead(
            {
                hint: $(this).data('hint') != undefined ? $(this).data('hint') == 1 : 0,
                highlight: $(this).data('highlight') != undefined ? $(this).data('highlight') == 1 : 0,
                minLength: $(this).data('minLength') != undefined ? $(this).data('minLength') : 1,
            },
            {
                name: 'search-results',
                display: 'post_title',
                source: new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: $(this).data('source'),
                        wildcard: '-QUERY'
                    }
                }),
                templates: {
                    notFound: $(this).data('template-empty') == undefined
                        ? undefined
                        : $(this).data('template-empty').replace(/\'/g, '"'),
                    suggestion: $(this).data('template-suggestion') == undefined
                        ? ''
                        : Handlebars.compile($(this).data('template-suggestion').replace(/\'/g, '"'))
                }
            }
        );
    });

    /**
     * Bind events
     * @since 1.0
     */
    if ($('#search-typeahead').data('onselect') != undefined
        && $('#search-typeahead').data('onselect') == 1
    ) {
        $('#search-typeahead').bind('typeahead:select', function (e, suggestion) {
            e.preventDefault();
            var location = suggestion.permalink == undefined
                ? suggestion.url
                : suggestion.permalink;
            if (location != undefined)
                window.location = location;
        });
    }

})(jQuery);
