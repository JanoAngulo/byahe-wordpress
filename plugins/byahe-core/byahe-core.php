<?php
/**
 * Plugin Name:       BYAHE Core
 * Description:       Custom blocks, pricing data and demo content for the BYAHE demo site.
 * Version:           0.1.0
 * Requires at least: 6.8
 * Requires PHP:      8.1
 * Author:            John Russel L. Angulo
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       byahe-core
 *
 * @package ByaheCore
 */

defined( 'ABSPATH' ) || exit;

define( 'BYAHE_CORE_VERSION', '0.1.0' );
define( 'BYAHE_CORE_DIR', __DIR__ );

require_once __DIR__ . '/inc/pricing.php';
require_once __DIR__ . '/inc/bindings.php';
require_once __DIR__ . '/inc/blocks.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/inc/class-seeder.php';
	require_once __DIR__ . '/inc/class-seed-command.php';
}
