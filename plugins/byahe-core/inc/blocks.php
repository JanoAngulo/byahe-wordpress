<?php
/**
 * Registers every block built into `build/blocks` from its manifest.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the plugin's blocks. Does nothing before the first build.
 */
function register_blocks(): void {
	$dir      = BYAHE_CORE_DIR . '/build/blocks';
	$manifest = BYAHE_CORE_DIR . '/build/blocks-manifest.php';
	if ( ! file_exists( $manifest ) ) {
		return;
	}
	wp_register_block_types_from_metadata_collection( $dir, $manifest );
}
add_action( 'init', __NAMESPACE__ . '\register_blocks' );
