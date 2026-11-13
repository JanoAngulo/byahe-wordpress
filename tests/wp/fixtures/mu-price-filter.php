<?php
/**
 * Test-only must-use plugin, mounted by `npm run serve:wp`.
 *
 * - `?byahe_test_price=1` on any request sets the Negosyo monthly price to 555,
 *   so specs can prove one data edit reaches every place a price shows.
 * - `byahe-test/v1/field` exposes Byahe\Core\tier_field() for direct checks.
 *
 * @package ByaheTests
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'byahe_core_pricing',
	static function ( array $pricing ): array {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- test fixture.
		if ( isset( $_GET['byahe_test_price'] ) ) {
			$pricing['tiers']['negosyo']['monthly'] = 555;
		}
		return $pricing;
	}
);

add_action(
	'rest_api_init',
	static function (): void {
		register_rest_route(
			'byahe-test/v1',
			'/field',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => static function ( WP_REST_Request $request ) {
					return array(
						'value' => \Byahe\Core\tier_field(
							(string) $request->get_param( 'tier' ),
							(string) $request->get_param( 'field' ),
							(string) ( $request->get_param( 'billing' ) ?? 'monthly' )
						),
					);
				},
			)
		);
	}
);
