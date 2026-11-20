<?php
/**
 * Title: Solutions: Food and cafés
 * Slug: byahe/solutions-food
 * Categories: byahe, featured
 * Description: Industry section with a pain point, three benefits, a customer quote and a photo with a menu-orders mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;
use function Byahe\Theme\photo;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'          => 'food',
		'bg'          => 'cream',
		'reverse'     => true,
		'last'        => false,
		'title'       => __( 'Food and cafés.', 'byahe' ),
		'pain'        => __( 'a long line at the counter while someone waits for change, and a cash count that never matches.', 'byahe' ),
		'bullets'     => array(
			__( 'A QR code on every table or receipt, so guests pay without the line', 'byahe' ),
			__( 'Split the bill by person, paid by QR, card or e-wallet', 'byahe' ),
			__( 'An end-of-day report by payment method', 'byahe' ),
		),
		'quote'       => array(
			'text'     => __( '“Saturday lunch used to back up to the door. Now half the tables pay from their seat.”', 'byahe' ),
			'name'     => 'Carlo Mendoza',
			'business' => __( 'Lutong Bahay Kitchen, Quezon City', 'byahe' ),
			'initials' => 'CM',
		),
		'media_class' => 'scene',
		'media'       => photo(
			'cafe-counter',
			array(
				'class'   => 'photo-duotone m-0 aspect-[4/3]',
				'duotone' => true,
			)
		) . '<!-- wp:byahe/ui-mockup {"variant":"menu-orders"} /-->',
	)
);
