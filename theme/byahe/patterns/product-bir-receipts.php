<?php
/**
 * Title: Product: BIR-ready receipts
 * Slug: byahe/product-bir-receipts
 * Categories: byahe, featured
 * Description: Feature section with three benefits and a live bir-receipt mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'      => 'bir-receipts',
		'bg'      => 'cream',
		'reverse' => false,
		'title'   => __( 'Receipts your bookkeeper will like.', 'byahe' ),
		'lead'    => __( 'Every payment issues a numbered receipt with the details filing season asks for.', 'byahe' ),
		'bullets' => array(
			__( 'Auto-numbered, with your TIN and the VAT breakdown', 'byahe' ),
			__( 'Sent to your customer the moment they pay', 'byahe' ),
			__( 'A monthly summary ready for filing', 'byahe' ),
		),
		'note'    => __( 'BYAHE is a demo company. The receipt shown is not a valid BIR document.', 'byahe' ),
		'media'   => '<!-- wp:byahe/ui-mockup {"variant":"bir-receipt"} /-->',
	)
);
