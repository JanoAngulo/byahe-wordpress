<?php
/**
 * Title: Solutions: Sari-sari and retail
 * Slug: byahe/solutions-retail
 * Categories: byahe, featured
 * Description: Industry section with a pain point, three benefits, a customer quote and a photo with a pos-sales mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;
use function Byahe\Theme\photo;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'          => 'retail',
		'bg'          => 'yellow',
		'reverse'     => false,
		'last'        => false,
		'title'       => __( 'Sari-sari and retail.', 'byahe' ),
		'pain'        => __( 'hunting for barya, and a notebook full of utang nobody wants to bring up.', 'byahe' ),
		'bullets'     => array(
			__( 'A printed QR Ph code at the counter, no card terminal needed', 'byahe' ),
			__( 'Suki credit tracked as small invoices, with gentle reminders', 'byahe' ),
			__( 'One sales total at closing time, not a shoebox of receipts', 'byahe' ),
		),
		'quote'       => array(
			'text'     => __( '“Hindi na ako naghahanap ng barya. Scan lang, tapos na.”', 'byahe' ),
			'name'     => 'Mang Oca',
			'business' => __( 'Tindahan ni Mang Oca, Malolos', 'byahe' ),
			'initials' => 'MO',
		),
		'media_class' => 'scene',
		'media'       => photo(
			'sari-sari',
			array(
				'class'   => 'photo-duotone m-0 aspect-[4/3]',
				'duotone' => true,
			)
		) . '<!-- wp:byahe/ui-mockup {"variant":"pos-sales"} /-->',
	)
);
