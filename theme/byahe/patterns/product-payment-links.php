<?php
/**
 * Title: Product: Payment links and QR
 * Slug: byahe/product-payment-links
 * Categories: byahe, featured
 * Description: Feature section with three benefits and a live payment-qr mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'      => 'payment-links',
		'bg'      => 'yellow',
		'reverse' => true,
		'title'   => __( 'One link. Every way to pay.', 'byahe' ),
		'lead'    => __( 'Turn any amount into a link or a QR Ph code. Your customer picks how to pay, and you see it the moment it clears.', 'byahe' ),
		'bullets' => array(
			__( 'A QR Ph code that works with any bank or e-wallet app', 'byahe' ),
			__( 'Cards, InstaPay and PESONet on the same page', 'byahe' ),
			__( 'Print the QR for your counter, no card terminal needed', 'byahe' ),
		),
		'media'   => '<!-- wp:byahe/ui-mockup {"variant":"payment-qr"} /-->',
	)
);
