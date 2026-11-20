<?php
/**
 * Title: Solutions: Online sellers
 * Slug: byahe/solutions-online-sellers
 * Categories: byahe, featured
 * Description: Industry section with a pain point, three benefits, a customer quote and a photo with a order-links mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;
use function Byahe\Theme\photo;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'          => 'online-sellers',
		'bg'          => 'cream',
		'reverse'     => true,
		'last'        => true,
		'title'       => __( 'Online sellers.', 'byahe' ),
		'pain'        => __( '“Paid na po” screenshots to check one by one, and orders that go quiet after the chat.', 'byahe' ),
		'bullets'     => array(
			__( 'A payment link for each order, sent right in the chat', 'byahe' ),
			__( 'Orders marked paid the moment the money clears, no screenshots', 'byahe' ),
			__( 'Unpaid links expire, so stock goes back on sale', 'byahe' ),
		),
		'quote'       => array(
			'text'     => __( '“No more zooming into screenshots at midnight. If it says paid, it’s paid.”', 'byahe' ),
			'name'     => 'Jessa Lim',
			'business' => __( 'Bulaklak Online, Cebu', 'byahe' ),
			'initials' => 'JL',
		),
		'media_class' => 'scene',
		'media'       => photo(
			'online-seller-packing',
			array(
				'class'   => 'photo-duotone m-0 aspect-[4/3]',
				'duotone' => true,
			)
		) . '<!-- wp:byahe/ui-mockup {"variant":"order-links"} /-->',
	)
);
