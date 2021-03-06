<?php
/**
 * Embed Functions
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

/***********************************************
 * EMBEDS
 ***********************************************/

/**
 * Embed code based on audio/video URL or provided embed code
 *
 * If content is URL, use oEmbed to get embed code. If content is not URL, assume it is
 * embed code and run do_shortcode() in case of [video], [audio] or [embed]
 *
 * @since 0.9
 * @param string $content URL
 */
function ctc_embed_code( $content ) {

	global $wp_embed;

	// Convert URL into media shortcode like [audio] or [video]
	if ( ctc_is_url( $content ) ) {
		$embed_code = $wp_embed->shortcode( array(), $content );
	}

	// HTML or shortcode embed may have been provided
	else {
		$embed_code = $content;
	}

	// Run shortcode
	// [video], [audio] or [embed] converted from URL or already existing in $content
	$embed_code = do_shortcode( $embed_code );

	// Return filtered
	return apply_filters( 'ctc_embed_code', $embed_code, $content );

}

/**
 * Responsive embeds JavaScript
 */
function ctc_responsive_embeds_enqueue_scripts() {

	// If theme supports this feature
	if ( current_theme_supports( 'ctc-responsive-embeds' ) ) {

		// FitVids.js
		wp_enqueue_script( 'fitvids', plugins_url( '/js/jquery.fitvids.js', CTC_FILE ), array( 'jquery' ), CTC_VERSION ); // bust cache on theme update

		// Responsive embeds script
		wp_enqueue_script( 'ctc-responsive-embeds', plugins_url( '/js/responsive-embeds.js', CTC_FILE ), array( 'fitvids' ), CTC_VERSION ); // bust cache on theme update

	}

}

add_action( 'wp_enqueue_scripts', 'ctc_responsive_embeds_enqueue_scripts' ); // front-end only (yes, wp_enqueue_scripts is correct for styles)

/**
 * Generic embeds
 *
 * This helps make embeds more generic by setting parameters to remove
 * related videos, set neutral colors, reduce branding, etc.
 *
 * Enable with: add_theme_support( 'ctc-generic-embeds' );
 *
 * @since 0.9
 * @param string $html Embed HTML code
 * @return string Modified embed HTML code
 */
function ctc_generic_embeds( $html ) {

	// Does theme support this?
	if ( current_theme_supports( 'ctc-generic-embeds' ) ) {

		// Get iframe source URL
		preg_match_all( '/<iframe[^>]+src=([\'"])(.+?)\1[^>]*>/i', $html, $matches );
		$url = ! empty( $matches[2][0] ) ? $matches[2][0] : '';

		// URL found
		if ( $url ) {

			$new_url = '';
			$source = '';
			$args = array();

			// YouTube
			if ( preg_match( '/youtube/i', $url ) ) {
				$source = 'youtube';
				$args = array(
					'wmode'				=> 'transparent',
					'rel'				=> '0', // don't show related videos at end
					'showinfo'			=> '0',
					'color'				=> 'white',
					'modestbranding'	=> '1'
				);
			}

			// Vimeo
			elseif ( preg_match( '/vimeo/i', $url ) ) {
				$source = 'vimeo';
				$args = array(
					'title'				=> '0',
					'byline'			=> '0',
					'portrait'			=> '0',
					'color'				=> 'ffffff'
				);
			}

			// Modify URL
			$args = apply_filters( 'ctc_generic_embeds_add_args', $args, $source );
			$new_url = add_query_arg( $args, $url );

			// Replace source with modified URL
			if ( $new_url != $url ) {
				$html = str_replace( $url, $new_url, $html );
			}

		}

	}

	return $html;

}

add_filter( 'embed_oembed_html', 'ctc_generic_embeds' );
