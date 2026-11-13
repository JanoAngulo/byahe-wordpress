<?php
/**
 * Pattern helpers and the BYAHE pattern category.
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * URL of a demo page. Built with home_url() so links work when WordPress
 * lives under a sub-path, as it does in Playground.
 *
 * @param string $slug Page slug, or 'home' for the front page.
 */
function page_url( string $slug ): string {
	return 'home' === $slug ? home_url( '/' ) : home_url( '/' . $slug . '/' );
}

/**
 * Navigation-link block attributes for a demo page. Uses the page's id when it
 * exists, so core marks the current page with aria-current.
 *
 * @param string $slug  Page slug.
 * @param string $label Link text.
 * @return string Block comment JSON.
 */
function nav_link_attrs( string $slug, string $label ): string {
	$attrs = array(
		'label' => $label,
		'url'   => page_url( $slug ),
	);
	$page  = get_page_by_path( $slug );
	if ( $page ) {
		$attrs += array(
			'type' => 'page',
			'id'   => $page->ID,
			'kind' => 'post-type',
		);
	}
	return (string) wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
}

/**
 * Registers the BYAHE pattern category.
 */
function register_pattern_category(): void {
	register_block_pattern_category( 'byahe', array( 'label' => __( 'BYAHE', 'byahe' ) ) );
}
add_action( 'init', __NAMESPACE__ . '\register_pattern_category' );
