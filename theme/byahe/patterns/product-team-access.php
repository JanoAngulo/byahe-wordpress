<?php
/**
 * Title: Product: Team access
 * Slug: byahe/product-team-access
 * Categories: byahe, featured
 * Description: Feature section with three benefits and a live team-roles mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'      => 'team-access',
		'bg'      => 'yellow',
		'reverse' => true,
		'title'   => __( 'Bring the whole crew.', 'byahe' ),
		'lead'    => __( 'Give everyone their own login with just the access they need. No more sharing one password at the counter.', 'byahe' ),
		'bullets' => array(
			__( 'Owner, cashier and bookkeeper roles out of the box', 'byahe' ),
			__( 'Cashiers take payments without seeing your totals', 'byahe' ),
			__( 'An activity log of who did what, and when', 'byahe' ),
		),
		'media'   => '<!-- wp:byahe/ui-mockup {"variant":"team-roles"} /-->',
	)
);
