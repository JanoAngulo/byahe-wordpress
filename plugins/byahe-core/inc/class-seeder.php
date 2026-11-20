<?php
/**
 * Builds the demo site: pages from the theme patterns, front page, permalinks.
 * Run by the WP-CLI command `wp byahe seed`.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Creates the demo pages from the theme's page patterns.
 */
class Seeder {

	/**
	 * Slugs the last seed() run skipped because their page pattern isn't registered.
	 *
	 * @var string[]
	 */
	public static array $skipped = array();

	/**
	 * Creates or updates the demo pages, sets the front page and pretty
	 * permalinks, and removes WordPress's sample content. Safe to run again.
	 *
	 * @return array<string, int> Page ids by slug.
	 */
	public static function seed(): array {
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

		$ids           = array();
		self::$skipped = array();
		foreach ( $pages as $slug => $title ) {
			// Without its pattern (another theme active, say) the page would be
			// saved empty, wiping whatever it holds. Leave it alone instead.
			if ( ! \WP_Block_Patterns_Registry::get_instance()->is_registered( 'byahe/page-' . $slug ) ) {
				self::$skipped[] = $slug;
				continue;
			}
			$ids[ $slug ] = self::upsert_page( $slug, $title );
		}

		if ( isset( $ids['home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $ids['home'] );
		}

		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		flush_rewrite_rules( false );

		self::delete_sample( 'hello-world', 'post' );
		self::delete_sample( 'sample-page', 'page' );

		return $ids;
	}

	/**
	 * Creates the page or replaces its content with the pattern's blocks.
	 *
	 * @param string $slug  Page slug; its pattern is `byahe/page-<slug>`.
	 * @param string $title Page title.
	 * @return int Page id.
	 */
	private static function upsert_page( string $slug, string $title ): int {
		$pattern = \WP_Block_Patterns_Registry::get_instance()->get_registered( 'byahe/page-' . $slug );
		$postarr = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => wp_slash( self::pattern_content( 'byahe/page-' . $slug ) ),
			// The page pattern's description is the page's meta description.
			'post_excerpt' => wp_slash( (string) ( $pattern['description'] ?? '' ) ),
		);

		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
		}

		$id = wp_insert_post( $postarr, true );
		return is_wp_error( $id ) ? 0 : (int) $id;
	}

	/**
	 * Block markup of a registered pattern with nested pattern references
	 * resolved, or '' when the pattern doesn't exist.
	 *
	 * Patterns escape apostrophes as &#039; (esc_html), but the block editor
	 * saves them as plain ' characters, which wptexturize then curls. Storing
	 * them the editor's way keeps the front end identical before and after a
	 * page is first saved in the editor.
	 *
	 * @param string $name Pattern name.
	 */
	private static function pattern_content( string $name ): string {
		if ( ! \WP_Block_Patterns_Registry::get_instance()->is_registered( $name ) ) {
			return '';
		}
		$blocks = parse_blocks( sprintf( '<!-- wp:pattern {"slug":"%s"} /-->', $name ) );
		return str_replace( '&#039;', "'", serialize_blocks( resolve_pattern_blocks( $blocks ) ) );
	}

	/**
	 * Permanently deletes one piece of WordPress's sample content.
	 *
	 * @param string $slug      Post slug.
	 * @param string $post_type Post type.
	 */
	private static function delete_sample( string $slug, string $post_type ): void {
		$post = get_page_by_path( $slug, OBJECT, $post_type );
		if ( $post ) {
			wp_delete_post( $post->ID, true );
		}
	}
}
