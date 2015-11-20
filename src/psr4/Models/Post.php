<?php

namespace Amostajo\Wordpress\TypeaheadAddon\Models;

use Amostajo\LightweightMVC\Model as Model;
use Amostajo\LightweightMVC\Traits\FindTrait as FindTrait;

/**
 * Post model.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\Wordpress\PostPickerAddon
 * @version 1.0
 */
class Post extends Model
{
    use FindTrait;

    /**
     * Aliases
     * @var array
     * @since 1.0
     */
    protected $aliases = [
        'excerpt'           => 'func_get_excerpt',
        'image_id'          => 'func_get_image_id',
        'image_url'         => 'func_get_image_url',
        'thumb_image_url'   => 'func_get_thumb_image_url',
        'permalink'         => 'func_get_permalink',
    ];

    /**
     * Hidden properties
     * @var array
     * @since 1.0
     */
    protected $hidden = [
        'post_content', 'guid', 'post_excerpt', 'post_author', 'image_url',
        'post_date_gmt', 'to_ping', 'pinged', 'comment_status', 'ping_status',
        'post_password', 'post_name', 'post_modified', 'post_modified_gmt',
        'post_content_filtered', 'post_parent', 'menu_order', 'post_mime_type',
        'comment_count', 'filter', 'ancestors', 'image_id',
    ];

    /**
     * Returns image url.
     * @since 1.0
     *
     * @return string
     */
    protected function get_image_id()
    {
        return get_post_thumbnail_id( $this->ID );
    }

    /**
     * Returns image url.
     * @since 1.0
     *
     * @return string
     */
    protected function get_image_url()
    {
        $id = $this->image_id;
        if ( $id ) {
            return wp_get_attachment_url( $id );
        }
        return;
    }

    /**
     * Returns thumbnail size image url.
     * @since 1.0
     *
     * @return string
     */
    protected function get_thumb_image_url()
    {
        $url = $this->image_url;
        if ( $url ) {
            return resize_image(
                $url,
                120,
                120
            );
        }
        return;
    }

    /**
     * Returns permalink.
     * @since 1.0
     *
     * @return string
     */
    protected function get_permalink()
    {
        return get_permalink( $this->ID );
    }

    /**
     * Returns excerpt.
     * @since 1.0
     *
     * @return string
     */
    protected function get_excerpt()
    {
        if ( $this->post_excerpt )
            return $this->post_excerpt;
        return $this->excerpt( $this->post_content );
    }

    /**
     * Excerpts a string.
     *
     * @param string $string     String to excerpt.
     * @param int    $char_limit Char limit to excerpt.
     * @param string $more       String to show add the end if string surpasses limit.
     *
     * @return string
     */
    private function excerpt( $string, $char_limit = 15, $more = '...' )
    {
        $string = strip_tags( $string );
        $return = preg_replace( "/^\W*((\w[\w'-]*\b\W*){1," . $char_limit . "}).*/ms", '\\1', $string );
        $return = str_replace( "\n", "", $return );
        if( str_word_count( $string ) !== str_word_count( $return ) ) {
			$return .= ' ' . $more;
        }
        return $return;
    }
}
