<?php
/**
 * Inline Lucide icons (ISC license) for the `byahe/icon` block.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Icon names the plugin ships, from inc/icons.json.
 *
 * @return list<string>
 */
function icon_names(): array {
	static $names = null;
	if ( null === $names ) {
		$names = (array) wp_json_file_decode( BYAHE_CORE_DIR . '/inc/icons.json', array( 'associative' => true ) );
	}
	return $names;
}

/**
 * Decorative inline SVG for a Lucide icon.
 *
 * @param string $name  Icon name from icon_names().
 * @param string $class_name Class for the svg element.
 * @return string SVG markup, or '' for an unknown icon.
 */
function icon_svg( string $name, string $class_name = '' ): string {
	if ( ! in_array( $name, icon_names(), true ) ) {
		return '';
	}
	$path = BYAHE_CORE_DIR . '/assets/icons/' . $name . '.svg';
	if ( ! file_exists( $path ) ) {
		return '';
	}

	// Lucide files start with a license comment; keep only the svg element.
	$svg = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
	$svg = (string) preg_replace( '/<!--.*?-->/s', '', $svg );

	$tags = new \WP_HTML_Tag_Processor( trim( $svg ) );
	if ( ! $tags->next_tag( 'svg' ) ) {
		return '';
	}
	foreach ( array( 'class', 'width', 'height' ) as $attribute ) {
		$tags->remove_attribute( $attribute );
	}
	if ( $class_name ) {
		$tags->set_attribute( 'class', $class_name );
	}
	$tags->set_attribute( 'aria-hidden', 'true' );
	$tags->set_attribute( 'focusable', 'false' );

	return $tags->get_updated_html();
}
