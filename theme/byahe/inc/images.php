<?php
/**
 * Stock photos shipped with the theme: block markup for patterns, plus
 * responsive srcset/sizes added at render time.
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

const PHOTO_WIDTHS = array( 480, 800, 1200 );

/**
 * Photo data from assets/images/photos.json, keyed by slug.
 *
 * @return array<string, array> Photo records (slug, alt, sizes, pageUrl, photographer…).
 */
function photos(): array {
	static $photos = null;
	if ( null === $photos ) {
		$path   = get_theme_file_path( 'assets/images/photos.json' );
		$list   = file_exists( $path ) ? wp_json_file_decode( $path, array( 'associative' => true ) ) : array();
		$photos = array_column( (array) $list, null, 'slug' );
	}
	return $photos;
}

/**
 * Core Image block markup for a theme photo.
 *
 * @param string $slug Photo slug from photos.json.
 * @param array  $args {
 *     Optional.
 *
 *     @type string $alt      Override the alt text ('' for decorative).
 *     @type string $class    Extra class names on the figure.
 *     @type bool   $duotone  Apply the navy and yellow duotone.
 *     @type bool   $priority Load eagerly with high fetch priority (the LCP image).
 * }
 */
function photo( string $slug, array $args = array() ): string {
	$data = photos()[ $slug ] ?? null;
	if ( ! $data ) {
		return '';
	}

	$alt     = $args['alt'] ?? $data['alt'];
	$classes = trim( ( $args['class'] ?? '' ) . ( empty( $args['priority'] ) ? '' : ' is-priority' ) );
	$url     = get_theme_file_uri( 'assets/images/' . $slug . '.webp' );

	$attrs = array(
		'sizeSlug'        => 'full',
		'linkDestination' => 'none',
	);
	if ( $classes ) {
		$attrs['className'] = $classes;
	}
	if ( ! empty( $args['duotone'] ) ) {
		$attrs['style'] = array( 'color' => array( 'duotone' => 'var:preset|duotone|navy-yellow' ) );
	}

	return sprintf(
		'<!-- wp:image %1$s --><figure class="wp-block-image size-full%2$s"><img src="%3$s" alt="%4$s"/></figure><!-- /wp:image -->',
		wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES ),
		$classes ? ' ' . esc_attr( $classes ) : '',
		esc_url( $url ),
		esc_attr( $alt )
	);
}

/**
 * Adds srcset, sizes, intrinsic size and loading hints to Image blocks that
 * show a theme photo, using the 480/800/1200 variants built next to it.
 *
 * @param string $content Rendered block.
 * @param array  $block   Parsed block.
 */
function responsive_photo( string $content, array $block ): string {
	$base = get_theme_file_uri( 'assets/images/' );
	$tags = new \WP_HTML_Tag_Processor( $content );
	if ( ! $tags->next_tag( 'img' ) ) {
		return $content;
	}
	$src = (string) $tags->get_attribute( 'src' );
	if ( ! str_starts_with( $src, $base ) || ! preg_match( '#/([a-z0-9-]+)\.webp$#', $src, $m ) ) {
		return $content;
	}
	$slug = $m[1];
	$data = photos()[ $slug ] ?? null;
	if ( ! $data ) {
		return $content;
	}

	$srcset = array();
	foreach ( PHOTO_WIDTHS as $width ) {
		if ( file_exists( get_theme_file_path( "assets/images/{$slug}-{$width}.webp" ) ) ) {
			$srcset[] = $base . "{$slug}-{$width}.webp {$width}w";
		}
	}
	if ( $srcset ) {
		$tags->set_attribute( 'srcset', implode( ', ', $srcset ) );
		$tags->set_attribute( 'sizes', $data['sizes'] ?? '100vw' );
	}

	$size = wp_getimagesize( get_theme_file_path( "assets/images/{$slug}.webp" ) );
	if ( $size ) {
		$tags->set_attribute( 'width', (string) $size[0] );
		$tags->set_attribute( 'height', (string) $size[1] );
	}

	$priority = str_contains( (string) ( $block['attrs']['className'] ?? '' ), 'is-priority' );
	if ( $priority ) {
		$tags->set_attribute( 'fetchpriority', 'high' );
		$tags->remove_attribute( 'loading' );
	} else {
		$tags->set_attribute( 'loading', 'lazy' );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/image', __NAMESPACE__ . '\responsive_photo', 10, 2 );
