<?php
/**
 * Post Functions
 *
 * These relate to posts in general -- all types.
 *
 * @package    ChurchThemes_Framework
 * @subpackage Functions
 * @copyright  Copyright (c) 2015, churchthemes.net
 * @copyright  Copyright (c) 2013 - 2015, Steven Gliebe
 * @link       https://github.com/churchthemes/church-theme-framework
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since      0.9
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add useful post classes
 *
 * @since 0.9
 * @param array $classes Classes currently being added to <body>
 * @return array Modified array of classes
 */
function ctc_add_post_classes( $classes ) {

	// Theme asks for this enhancement?
	if ( current_theme_supports( 'ctc-post-classes' ) ) {

		// Has featured image?
		if ( has_post_thumbnail() ) {
			$classes[] = 'ctc-has-image';
		} else {
			$classes[] = 'ctc-no-image';
		}

	}

	return $classes;

}

add_filter( 'post_class', 'ctc_add_post_classes' );

/**
 * Get first ordered post
 *
 * Get first post according to manual order
 *
 * @since 1.0.9
 * @param string $post_type Post type to use
 * @return Array Post data
 */
function ctc_first_ordered_post( $post_type ) {

	$post = array();

	// Get first post
	$posts = get_posts( array(
		'post_type'			=> $post_type,
		'orderby'			=> 'menu_order', // first manually ordered
		'order'				=> 'ASC',
		'numberposts'		=> 1,
		'suppress_filters'	=> false // assist multilingual
	) );

	// Get post as array
	if ( isset( $posts[0] ) ) {
		$post = (array) $posts[0];
	}

	// Return filtered
	return apply_filters( 'ctc_first_ordered_post', $post );

}
