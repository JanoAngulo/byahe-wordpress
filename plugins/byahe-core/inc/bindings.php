<?php
/**
 * Block Bindings source `byahe/pricing`: lets core Paragraph and Heading
 * blocks show a tier's name, price, period or badge from the pricing data.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the pricing bindings source.
 */
function register_bindings(): void {
	register_block_bindings_source(
		'byahe/pricing',
		array(
			'label'              => __( 'BYAHE pricing', 'byahe-core' ),
			'get_value_callback' => static function ( array $source_args ): string {
				return tier_field(
					(string) ( $source_args['tier'] ?? '' ),
					(string) ( $source_args['field'] ?? '' ),
					(string) ( $source_args['billing'] ?? 'monthly' )
				);
			},
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_bindings' );
