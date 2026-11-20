<?php
/**
 * Title: Solutions: Freelancers and agencies
 * Slug: byahe/solutions-freelancers
 * Categories: byahe, featured
 * Description: Industry section with a pain point, three benefits, a customer quote and a photo with a retainer-invoice mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;
use function Byahe\Theme\photo;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'          => 'freelancers',
		'bg'          => 'yellow',
		'reverse'     => false,
		'last'        => false,
		'title'       => __( 'Freelancers and agencies.', 'byahe' ),
		'pain'        => __( 'chasing clients thirty days after the work is done, and rebuilding the same invoice every month.', 'byahe' ),
		'bullets'     => array(
			__( 'Retainer invoices that send themselves on the same day each month', 'byahe' ),
			__( 'Deposits and milestone billing for project work', 'byahe' ),
			__( 'Clients abroad pay by card, and you receive pesos', 'byahe' ),
		),
		'quote'       => array(
			'text'     => __( '“My retainers go out on the 1st without me. I spend that hour designing instead.”', 'byahe' ),
			'name'     => 'Bea Santos',
			'business' => __( 'Studio Bahaghari, Makati', 'byahe' ),
			'initials' => 'BS',
		),
		'media_class' => 'scene',
		'media'       => photo(
			'freelancer-desk',
			array(
				'class'   => 'photo-duotone m-0 aspect-[4/3]',
				'duotone' => true,
			)
		) . '<!-- wp:byahe/ui-mockup {"variant":"retainer-invoice"} /-->',
	)
);
