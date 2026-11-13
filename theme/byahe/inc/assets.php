<?php
/**
 * Stylesheet, editor styles, font preloads and the skip link.
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Version string for a theme file: its modification time, so caches bust on rebuild.
 *
 * @param string $relative Path inside the theme.
 */
function asset_version( string $relative ): string {
	$path = get_theme_file_path( $relative );
	return file_exists( $path ) ? (string) filemtime( $path ) : VERSION;
}

/**
 * Loads the compiled stylesheet on the front end.
 */
function enqueue_styles(): void {
	wp_enqueue_style(
		'byahe-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array(),
		asset_version( 'assets/css/theme.css' )
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles' );

/**
 * Loads the same stylesheet inside the editor canvas, so the editor matches the front end.
 */
function editor_styles(): void {
	add_editor_style( 'assets/css/theme.css' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\editor_styles' );

/**
 * Preloads the display and body fonts so headlines don't reflow when they arrive.
 */
function preload_fonts(): void {
	foreach ( array( 'cubao-free-variable.woff2', 'inter-latin-wght-normal.woff2' ) as $file ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/' . $file ) )
		);
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\preload_fonts', 1 );

/**
 * Prints a skip link in the page markup. Core's block-theme skip link is
 * injected by JavaScript, so it is missing when scripts don't run.
 */
function skip_link(): void {
	printf( '<a class="skip-link" href="#main">%s</a>', esc_html__( 'Skip to content', 'byahe' ) );
}
add_action( 'wp_body_open', __NAMESPACE__ . '\skip_link', 1 );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_block_template_skip_link' );
remove_action( 'wp_footer', 'the_block_template_skip_link' );
