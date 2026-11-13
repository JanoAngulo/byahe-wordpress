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
 * Creates the demo pages from the theme's page patterns.
 */
class Seed_Command {

	/**
	 * Creates or updates the demo pages, sets the front page and pretty
	 * permalinks, and removes WordPress's sample content. Safe to run again.
	 *
	 * ## EXAMPLES
	 *
	 *     wp byahe seed
	 */
	public function __invoke(): void {
		// Seeding runs without a user, so kses would strip block markup.
		kses_remove_filters();

		/**
		 * Filters the demo pages, as slug => title.
		 *
		 * @param array<string, string> $pages Pages to seed.
		 */
		$pages = apply_filters(
			'byahe_core_seed_pages',
			array(
				'home'      => __( 'Home', 'byahe-core' ),
				'product'   => __( 'Product', 'byahe-core' ),
				'pricing'   => __( 'Pricing', 'byahe-core' ),
				'solutions' => __( 'Solutions', 'byahe-core' ),
				'about'     => __( 'About', 'byahe-core' ),
			)
		);

		$ids = array();
		foreach ( $pages as $slug => $title ) {
			$ids[ $slug ] = $this->upsert_page( $slug, $title );
			WP_CLI::log( sprintf( 'Seeded %s (#%d)', $slug, $ids[ $slug ] ) );
		}

		if ( isset( $ids['home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $ids['home'] );
		}

		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		flush_rewrite_rules( false );

		$this->delete_sample( 'hello-world', 'post' );
		$this->delete_sample( 'sample-page', 'page' );

		WP_CLI::success( 'Demo content ready.' );
	}

	/**
	 * Creates the page or replaces its content with the pattern's blocks.
	 *
	 * @param string $slug  Page slug; its pattern is `byahe/page-<slug>`.
	 * @param string $title Page title.
	 * @return int Page id.
	 */
	private function upsert_page( string $slug, string $title ): int {
		$postarr = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => wp_slash( $this->pattern_content( 'byahe/page-' . $slug ) ),
		);

		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
		}

		$id = wp_insert_post( $postarr, true );
		if ( is_wp_error( $id ) ) {
			WP_CLI::error( $id->get_error_message() );
		}
		return (int) $id;
	}

	/**
	 * Block markup of a registered pattern with nested pattern references
	 * resolved, or '' when the pattern doesn't exist.
	 *
	 * @param string $name Pattern name.
	 */
	private function pattern_content( string $name ): string {
		if ( ! \WP_Block_Patterns_Registry::get_instance()->is_registered( $name ) ) {
			return '';
		}
		$blocks = parse_blocks( sprintf( '<!-- wp:pattern {"slug":"%s"} /-->', $name ) );
		return serialize_blocks( resolve_pattern_blocks( $blocks ) );
	}

	/**
	 * Permanently deletes one piece of WordPress's sample content.
	 *
	 * @param string $slug      Post slug.
	 * @param string $post_type Post type.
	 */
	private function delete_sample( string $slug, string $post_type ): void {
		$post = get_page_by_path( $slug, OBJECT, $post_type );
		if ( $post ) {
			wp_delete_post( $post->ID, true );
		}
	}
}

WP_CLI::add_command( 'byahe seed', Seed_Command::class );
