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
	 * Wordpress HOOKS.
	 * @since 1.0
	 */
	public function init()
	{
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
}
