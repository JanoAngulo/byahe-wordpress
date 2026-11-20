<?php
/**
 * WP-CLI command that builds the demo site: `wp byahe seed`.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

use WP_CLI;

defined( 'ABSPATH' ) || exit;

/**
 * Seeds the demo pages.
 */
class Seed_Command {

	/**
	 * Creates or updates the demo pages from the theme's page patterns, sets
	 * the front page and pretty permalinks, and removes sample content.
	 *
	 * ## EXAMPLES
	 *
	 *     wp byahe seed
	 */
	public function __invoke(): void {
		$ids = Seeder::seed();
		foreach ( Seeder::$skipped as $slug ) {
			WP_CLI::warning( sprintf( 'Skipped the %1$s page: the byahe/page-%1$s pattern is not registered. Is the BYAHE theme active?', $slug ) );
		}
		if ( ! $ids ) {
			WP_CLI::error( 'No pages seeded.' );
		}
		foreach ( $ids as $slug => $id ) {
			if ( ! $id ) {
				WP_CLI::error( sprintf( 'Could not save the %s page.', $slug ) );
			}
			WP_CLI::log( sprintf( 'Seeded %s (#%d)', $slug, $id ) );
		}
		WP_CLI::success( 'Demo content ready.' );
	}
}

WP_CLI::add_command( 'byahe seed', Seed_Command::class );
