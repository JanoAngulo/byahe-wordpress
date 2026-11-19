<?php
/**
 * Title: Product: Auto reminders
 * Slug: byahe/product-reminders
 * Categories: byahe, featured
 * Description: Feature section with three benefits and a live reminders mockup.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\feature_section;

echo feature_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup, escaped inside.
	array(
		'id'      => 'reminders',
		'bg'      => 'cream',
		'reverse' => false,
		'title'   => __( 'Paalala, on autopilot.', 'byahe' ),
		'lead'    => __( 'No more awkward follow-ups. BYAHE sends polite reminders on a schedule you set, and stops as soon as they pay.', 'byahe' ),
		'bullets' => array(
			__( 'A friendly nudge before the due date, a firmer one after', 'byahe' ),
			__( 'Reminders stop the moment the invoice is paid', 'byahe' ),
			__( 'Edit the wording, in English or Tagalog', 'byahe' ),
		),
		'media'   => '<!-- wp:byahe/ui-mockup {"variant":"reminders"} /-->',
	)
);
