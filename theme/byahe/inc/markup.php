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
