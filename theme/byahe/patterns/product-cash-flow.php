<?php
/**
 * Title: Product: Cash flow
 * Slug: byahe/product-cash-flow
 * Categories: byahe, featured
 * Description: Feature section with three benefits and a live cashflow-chart mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'      => 'cash-flow',
		'bg'      => 'yellow',
		'reverse' => true,
		'title'   => __( 'Know what\'s coming in.', 'byahe' ),
		'lead'    => __( 'See this month next to the last six, what\'s expected this week, and who\'s running late, without opening a spreadsheet.', 'byahe' ),
		'bullets' => array(
			__( 'Money in and money out, month by month', 'byahe' ),
			__( 'What\'s due this week and which invoices are overdue', 'byahe' ),
			__( 'One-click export for your accountant', 'byahe' ),
		),
		'media'   => '<!-- wp:byahe/ui-mockup {"variant":"cashflow-chart"} /-->',
	)
);
