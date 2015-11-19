<?php

namespace Amostajo\Wordpress\TypeaheadAddon\Controllers;

use Exception;
use WP_Query;
use Amostajo\WPPluginCore\Log;
use Amostajo\WPPluginCore\Cache;
use Amostajo\LightweightMVC\Controller;
use Amostajo\LightweightMVC\Request;
use Amostajo\Wordpress\TypeaheadAddon\Models\Post;

/**
 * Typeahead Controller handles all functionality related to addon.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\Wordpress\PostPickerAddon
 * @version 1.0
 */
class TypeaheadController extends Controller
{
	/**
	 * Displayes json for search results.
	 * @since 1.0
	 */
	public function json()
	{
		$data = [];
		try {
			// Search args
			$args = apply_filters(
				'addon_typeahead_query',
				[
					'posts_per_page'	=> Request::input( 'posts_per_page', get_option( 'addon_typeahead_limit', 5 ) ),
					'post_type'			=> Request::input( 'post_type', 'post' ),
					'post_status'		=> Request::input( 'post_status', 'publish' ),
					's'					=> Request::input( 's', '' ),
				]
			);
			// Results
			$data = Cache::remember(
				'addon_typeahead_res_' . $args['posts_per_page'] . '_' . urlencode( $args['s'] ),
				15,
				function () use( &$args ) {
					$query = new WP_Query( $args );
					$data = [];
					while ( $query->have_posts() ) {
						$query->the_post();
						$data[] = apply_filters( 'addon_typeahead_post', get_post( null, OBJECT ) );
					}
					return $data;
				}
			);
			// Filter results
			$data = Cache::remember(
				'addon_typeahead_filteredres_' . $args['posts_per_page'] . '_' . urlencode( $args['s'] ),
				15,
				function () use( &$data, &$args ) {
					return apply_filters( 'addon_typeahead_data', $data, $args );
				}
			);
		} catch ( \Exception $e ) {
			Log::error( $e );
			$data = [];
		}

		// Render JSON
		header( 'Content-Type: application/json' );
		if ( get_option( 'addon_typeahead_crossdomain', false ) ) {
        	header( 'Access-Control-Allow-Origin: *' );
		}
		echo json_encode( $data );
		die;
	}

	/**
	 * Filters post and transforms it into a model.
	 * @since 1.0
	 *
	 * @param object $post WP_Post
	 *
	 * @return object Model
	 */
	public function filter( $post )
	{
		if ( !is_object( $post ) || !is_a( $post , 'WP_Post' ) )
			return $post;

		$model = new Post();
		return $model->from_post( $post )->to_array();
	}
}
