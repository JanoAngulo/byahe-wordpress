<?php
/**
 * Meta description from the page excerpt.
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function (): void {
		add_post_type_support( 'page', 'excerpt' );
	}
);

/**
 * Prints the excerpt of the page being viewed as its meta description.
 */
function meta_description(): void {
	if ( ! is_singular() || ! has_excerpt() ) {
		return;
	}
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) );
}
add_action( 'wp_head', __NAMESPACE__ . '\meta_description', 1 );
