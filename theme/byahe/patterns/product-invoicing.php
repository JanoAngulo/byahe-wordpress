<?php
/**
 * Title: Product: Invoicing
 * Slug: byahe/product-invoicing
 * Categories: byahe, featured
 * Description: Feature section with three benefits and a live invoice mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'      => 'invoicing',
		'bg'      => 'cream',
		'reverse' => false,
		'title'   => __( 'Invoices that look like you.', 'byahe' ),
		'lead'    => __( 'Set up your business details once. Every invoice after that takes under a minute.', 'byahe' ),
		'bullets' => array(
			__( 'Your logo, TIN and address on every invoice', 'byahe' ),
			__( 'Line items, discounts and 12% VAT worked out for you', 'byahe' ),
			__( 'Send by SMS, Messenger, email or a plain link', 'byahe' ),
		),
		'media'   => '<!-- wp:byahe/ui-mockup {"variant":"invoice"} /-->',
	)
);
