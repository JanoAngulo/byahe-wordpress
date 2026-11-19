<?php
/**
 * Server state for the byahe/pricing Interactivity store, and the pricing
 * data the card block shows in the editor.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the initial byahe/pricing state. The price and period are
 * derived per card from its context, so the server renders the same text
 * the browser would.
 */
function pricing_interactivity_state(): void {
	wp_interactivity_state(
		'byahe/pricing',
		array(
			'isYearly' => false,
			'status'   => '',
			'messages' => array(
				'yearly'  => __( 'Showing yearly prices. Two months libre on every paid plan.', 'byahe-core' ),
				'monthly' => __( 'Showing monthly prices.', 'byahe-core' ),
			),
			'price'    => static function () {
				$context = wp_interactivity_get_context( 'byahe/pricing' );
				$state   = wp_interactivity_state( 'byahe/pricing' );
				return empty( $state['isYearly'] ) ? ( $context['monthly'] ?? '' ) : ( $context['yearly'] ?? '' );
			},
			'period'   => static function () {
				$context = wp_interactivity_get_context( 'byahe/pricing' );
				$state   = wp_interactivity_state( 'byahe/pricing' );
				return empty( $state['isYearly'] ) ? ( $context['periodMonthly'] ?? '' ) : ( $context['periodYearly'] ?? '' );
			},
		)
	);
}

/**
 * Prints the tier names and monthly prices for the pricing card's editor preview.
 */
function editor_pricing_data(): void {
	$tiers = array();
	foreach ( array_keys( pricing()['tiers'] ) as $slug ) {
		$tiers[ $slug ] = array(
			'name'   => tier_field( $slug, 'name' ),
			'price'  => tier_field( $slug, 'price' ),
			'period' => tier_field( $slug, 'period' ),
			'badge'  => tier_field( $slug, 'badge' ),
			'isText' => null === pricing()['tiers'][ $slug ]['monthly'],
		);
	}
	wp_add_inline_script(
		'byahe-pricing-card-editor-script',
		'window.byahePricing = ' . wp_json_encode( array( 'tiers' => $tiers ) ) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\editor_pricing_data' );
