<?php
/**
 * Global functions included in package.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\Wordpress\PostPickerAddon
 * @version 1.0.0
 */

if ( !function_exists( 'typeahead_search_form' ) )
{
    /**
     * Creates and displays typeahead search form.
     * @since 1.0.0
     *
     * @param array $args Search args.
     *
     * @return void
     */
    function typeahead_search_form( $args = [] )
    {
        wp_enqueue_style( 'wp-typeahead' );
        wp_enqueue_script( 'wp-typeahead' );
        // Set wildcard
        $args['s'] = '-QUERY';
        // Display
        echo typeahead_get_view( 'form', [
            'source_url'           => admin_url( 'admin-ajax.php?action=addon_typeahead'
                                        . ( empty( $args )
                                            ? ''
                                            : '&' . http_build_query( $args )
                                        )
                                    ),
            'template_empty'        => typeahead_get_view( 'template-empty', [], true ),
            'template_suggestion'   => typeahead_get_view( 'template-suggestion', [], true ),
        ] );
    }
}

if ( !function_exists( 'typeahead_get_view' ) )
{
    /**
     * Returns a view located in the add-on.
     * @since 1.0.0
     *
     * @param string $template       Template name.
     * @param array  $params         Template parameters.
     * @param string $replace_quotes Flag that indicates if double quotes should be replaced.
     *
     * @return string
     */
    function typeahead_get_view( $template, $params = [], $replace_quotes = false )
    {
        // Get template
        $theme_path = get_template_directory() . '/views/addons/typeahead/' . $template . 'php';
        $addon_path = __DIR__ . '/../psr4/views/addons/typeahead/' . $template . '.php';
        // Check path
        $path = is_readable( $theme_path )
            ? $theme_path
            : ( is_readable( $addon_path )
                ? $addon_path
                : null
            );
        if ( ! empty( $path ) ) {
            ob_start();
            extract( $params );
            include( $path );
            $view = ob_get_clean();
            return $replace_quotes
                ? preg_replace('/\"/', '\'', $view)
                : $view;
        }
        return;
    }
}