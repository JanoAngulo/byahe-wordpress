<?php
/**
 * Block style variations editors can pick in the inspector. Their CSS lives
 * in src/wp.css and the shared prototype components.css.
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the BYAHE block styles.
 */
function register_block_styles(): void {
	$styles = array(
		'core/group'     => array(
			'hard-shadow'    => __( 'Hard shadow', 'byahe' ),
			'hard-shadow-lg' => __( 'Hard shadow, large', 'byahe' ),
		),
		'core/button'    => array(
			'secondary' => __( 'Secondary', 'byahe' ),
			'accent'    => __( 'Accent', 'byahe' ),
		),
		'core/separator' => array(
			'stripe' => __( 'Jeepney stripe', 'byahe' ),
		),
		'core/paragraph' => array(
			'pill' => __( 'Status pill', 'byahe' ),
		),
	);

	foreach ( $styles as $block => $variations ) {
		foreach ( $variations as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\register_block_styles' );
