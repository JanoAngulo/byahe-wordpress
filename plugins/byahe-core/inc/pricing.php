<?php
/**
 * Pricing data: defined once, read by the pricing blocks and the bindings source.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Yearly billing charges ten months: two months free.
 */
const YEARLY_MONTHS = 10;

/**
 * Tier and fee data for the fictional plans.
 *
 * A tier's yearly price is its monthly price times YEARLY_MONTHS unless the
 * data (or the filter) sets `yearly` itself, so one monthly edit moves both.
 *
 * @return array{tiers: array<string, array{name: string, monthly: ?int, yearly: ?int, label: ?string, period_monthly: string, period_yearly: string, badge: ?string}>, fees: list<array{method: string, fee: string}>}
 */
function pricing(): array {
	$per_month = __( 'per month', 'byahe-core' );
	$per_year  = __( 'per year', 'byahe-core' );

	$defaults = array(
		'tiers' => array(
			'libre'      => array(
				'name'           => __( 'Libre', 'byahe-core' ),
				'monthly'        => 0,
				'label'          => null,
				'period_monthly' => __( 'forever', 'byahe-core' ),
				'period_yearly'  => __( 'forever', 'byahe-core' ),
				'badge'          => null,
			),
			'negosyo'    => array(
				'name'           => __( 'Negosyo', 'byahe-core' ),
				'monthly'        => 499,
				'label'          => null,
				'period_monthly' => $per_month,
				'period_yearly'  => $per_year,
				'badge'          => __( 'Most popular', 'byahe-core' ),
			),
			'pro'        => array(
				'name'           => __( 'Pro', 'byahe-core' ),
				'monthly'        => 1499,
				'label'          => null,
				'period_monthly' => $per_month,
				'period_yearly'  => $per_year,
				'badge'          => null,
			),
			'enterprise' => array(
				'name'           => __( 'Enterprise', 'byahe-core' ),
				'monthly'        => null,
				'yearly'         => null,
				'label'          => __( "Let's talk", 'byahe-core' ),
				'period_monthly' => __( 'custom pricing', 'byahe-core' ),
				'period_yearly'  => __( 'custom pricing', 'byahe-core' ),
				'badge'          => null,
			),
		),
		'fees'  => array(
			array(
				'method' => __( 'QR Ph', 'byahe-core' ),
				'fee'    => '1.0%',
			),
			array(
				'method' => __( 'Credit and debit cards', 'byahe-core' ),
				'fee'    => '2.9% + ₱15',
			),
			array(
				'method' => __( 'Bank transfer (InstaPay, PESONet)', 'byahe-core' ),
				'fee'    => __( '₱10 flat', 'byahe-core' ),
			),
		),
	);

	/**
	 * Filters the BYAHE plan and fee data.
	 *
	 * @param array $defaults Tier and fee data.
	 */
	$pricing = apply_filters( 'byahe_core_pricing', $defaults );
	foreach ( $pricing['tiers'] as &$tier ) {
		if ( ! array_key_exists( 'yearly', $tier ) ) {
			$tier['yearly'] = is_int( $tier['monthly'] ) ? $tier['monthly'] * YEARLY_MONTHS : null;
		}
	}
	unset( $tier );
	return $pricing;
}

/**
 * Formats a whole-peso amount, e.g. 1499 → "₱1,499".
 *
 * @param int $amount Amount in pesos.
 */
function format_peso( int $amount ): string {
	return '₱' . number_format( $amount );
}

/**
 * One display value for a tier.
 *
 * @param string $tier    Tier slug: libre, negosyo, pro or enterprise.
 * @param string $field   name, price, period or badge.
 * @param string $billing monthly or yearly.
 * @return string The value, or '' for an unknown tier or field.
 */
function tier_field( string $tier, string $field, string $billing = 'monthly' ): string {
	$tiers = pricing()['tiers'];
	if ( ! isset( $tiers[ $tier ] ) ) {
		return '';
	}
	$data    = $tiers[ $tier ];
	$billing = 'yearly' === $billing ? 'yearly' : 'monthly';

	switch ( $field ) {
		case 'name':
			return (string) $data['name'];
		case 'price':
			$amount = $data[ $billing ];
			return null === $amount ? (string) $data['label'] : format_peso( (int) $amount );
		case 'period':
			return (string) $data[ 'period_' . $billing ];
		case 'badge':
			return (string) ( $data['badge'] ?? '' );
		default:
			return '';
	}
}
