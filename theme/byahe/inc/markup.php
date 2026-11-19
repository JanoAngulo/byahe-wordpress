<?php
/**
 * Small render-time markup fixes for core blocks that have no setting for them.
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Hides initials avatars from screen readers; the name next to them is read instead.
 *
 * @param string $content Rendered paragraph.
 */
function hide_avatar_initials( string $content ): string {
	$tags = new \WP_HTML_Tag_Processor( $content );
	if ( $tags->next_tag( 'p' ) && $tags->has_class( 'avatar' ) ) {
		$tags->set_attribute( 'aria-hidden', 'true' );
		return $tags->get_updated_html();
	}
	return $content;
}
add_filter( 'render_block_core/paragraph', __NAMESPACE__ . '\hide_avatar_initials' );

/**
 * Gives the pricing card grid list semantics; core Group can't render a ul
 * around blocks that render their own wrappers.
 *
 * @param string $content Rendered group.
 */
function tier_list_role( string $content ): string {
	$tags = new \WP_HTML_Tag_Processor( $content );
	if ( $tags->next_tag() && $tags->has_class( 'tier-list' ) ) {
		$tags->set_attribute( 'role', 'list' );
		return $tags->get_updated_html();
	}
	return $content;
}
add_filter( 'render_block_core/group', __NAMESPACE__ . '\tier_list_role' );

/**
 * Turns a core Table with the class table-scroll into the prototype's data
 * table: a focusable scroll region, a real <caption>, a highlighted
 * Negosyo column, and Yes / — cells shown as marks with screen-reader text.
 * Editors keep typing plain text in the table.
 *
 * @param string $content Rendered table block.
 */
function data_table( string $content ): string {
	$tags = new \WP_HTML_Tag_Processor( $content );
	if ( ! $tags->next_tag( 'figure' ) || ! $tags->has_class( 'table-scroll' ) ) {
		return $content;
	}

	$caption = '';
	if ( preg_match( '#<figcaption[^>]*>(.*?)</figcaption>#s', $content, $match ) ) {
		$caption = $match[1];
	}
	$modifiers = array_values(
		array_filter(
			iterator_to_array( $tags->class_list(), false ),
			static fn( $class_name ) => str_starts_with( $class_name, 'data-table--' )
		)
	);
	foreach ( $modifiers as $class_name ) {
		$tags->remove_class( $class_name );
	}
	$tags->set_attribute( 'tabindex', '0' );
	$tags->set_attribute( 'role', 'region' );
	$tags->set_attribute( 'aria-label', html_entity_decode( wp_strip_all_tags( $caption ), ENT_QUOTES ) );

	if ( $tags->next_tag( 'table' ) ) {
		$tags->add_class( 'data-table' );
		foreach ( $modifiers as $class_name ) {
			$tags->add_class( $class_name );
		}
	}

	// Highlight the third column (Negosyo) on the comparison table.
	if ( in_array( 'data-table--compare', $modifiers, true ) ) {
		$column = 0;
		while ( $tags->next_tag() ) {
			$tag = $tags->get_tag();
			if ( 'TR' === $tag && ! $tags->is_tag_closer() ) {
				$column = 0;
			} elseif ( in_array( $tag, array( 'TH', 'TD' ), true ) && ! $tags->is_tag_closer() ) {
				++$column;
				if ( 3 === $column ) {
					$tags->add_class( 'is-featured' );
				}
			}
		}
	}

	$html = $tags->get_updated_html();
	$html = (string) preg_replace( '#<figcaption[^>]*>.*?</figcaption>#s', '', $html );
	if ( $caption ) {
		$html = (string) preg_replace( '#(<table[^>]*>)#', '$1<caption>' . $caption . '</caption>', $html, 1 );
	}

	$yes  = '<span class="mark mark--yes"><span class="sr-only">' . esc_html__( 'Included', 'byahe' ) . '</span></span>';
	$no   = '<span class="mark">–<span class="sr-only">' . esc_html__( 'Not included', 'byahe' ) . '</span></span>';
	$html = (string) preg_replace( '#(<td[^>]*>)\s*Yes\s*</td>#', '$1' . $yes . '</td>', $html );
	$html = (string) preg_replace( '~(<td[^>]*>)\s*(?:—|&mdash;|&#8212;)\s*</td>~u', '$1' . $no . '</td>', $html );

	return $html;
}
add_filter( 'render_block_core/table', __NAMESPACE__ . '\data_table' );
