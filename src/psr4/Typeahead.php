<?php

namespace Amostajo\Wordpress\TypeaheadAddon;

use Amostajo\WPPluginCore\Addon;

/**
 * Post Picker add-on.
 * For Wordpress Plugin or Wordpress Theme templates.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\Wordpress\PostPickerAddon
 * @version 1.0
 */
class Typeahead extends Addon
{
    /**
     * Addon init function from within main.
     * @since 1.0.0
     */
    public function typeahead()
    {
        wp_enqueue_style( 'wp-typeahead' );
        wp_enqueue_script( 'wp-typeahead' );
    }

    /**
     * Wordpress HOOKS.
     * @since 1.0
     */
    public function init()
    {
        add_action( 'init', [ &$this, 'config' ] );
        add_action( 'wp_ajax_nopriv_addon_typeahead', [ &$this, 'typeahead_json' ] );
        add_action( 'wp_ajax_addon_typeahead', [ &$this, 'typeahead_json' ] );
        add_filter( 'addon_typeahead_post', [ &$this, 'typeahead_filter' ], 100, 2 );
    }

    /**
     * Displayes queried POSTS in JSON format.
     * @since 1.0
     */
    public function typeahead_json()
    {
        $this->mvc->call( 'TypeaheadController@json' );
    }

    /**
     * Filters post and transforms it into a model.
     * @since 1.0
     *
     * @param object $post WP_Post
     *
     * @return object Model
     */
    public function typeahead_filter( $post )
    {
        return $this->mvc->action( 'TypeaheadController@filter', $post );
    }

    /**
     * Addon configuration.
     * Registers dependencies.
     * @since 1.0
     */
    public function config()
    {
        wp_register_script(
            'typeahead-all',
            plugins_url( '../../assets/build/typeahead-all.min.js' , __FILE__ ),
            [ 'jquery' ],
            '1.0.0',
            true
        );
        wp_register_script(
            'wp-typeahead',
            plugins_url( '../../assets/build/wp-typeahead.min.js' , __FILE__ ),
            [ 'typeahead-all' ],
            '1.0.0',
            true
        );
        wp_register_style(
            'wp-typeahead',
            plugins_url( '../../assets/build/wp-typeahead.min.css' , __FILE__ ),
            [],
            '1.0.0'
        );
    }
}
