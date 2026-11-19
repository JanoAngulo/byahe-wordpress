<?php
/**
 * UI mockup variant: menu-orders. Markup mirrors prototype/partials/mockups/menu-orders.html.
 *
 * @package ByaheCore
 *
 * @var array $f Resolved fields.
 * @var array $l Resolved lists.
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template scope, included from render.php.
?>
<div class="ui-head">
	<p class="ui-title"><?php echo esc_html( $f['title'] ); ?></p>
	<span class="ui-muted"><span class="num"><?php echo esc_html( $f['tables'] ); ?></span> <?php esc_html_e( 'tables', 'byahe-core' ); ?></span>
</div>
<?php foreach ( $l['orders'] as $r ) : ?>
<div class="ui-row"><span><span class="ui-strong"><?php echo esc_html( $r['name'] ); ?></span><br><span class="ui-muted"><span class="num"><?php echo esc_html( $r['items'] ); ?></span> <?php esc_html_e( 'items', 'byahe-core' ); ?></span></span><span class="num ui-strong"><?php echo esc_html( $r['amount'] ); ?></span><span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $r['status'] ) ); ?>"><?php echo esc_html( $r['status'] ); ?></span></div>
<?php endforeach; ?>
<div class="ui-stats">
	<div class="ui-stat"><p class="ui-muted"><?php esc_html_e( 'By QR Ph', 'byahe-core' ); ?></p><p class="ui-total num"><?php echo esc_html( $f['qr'] ); ?></p></div>
	<div class="ui-stat"><p class="ui-muted"><?php esc_html_e( 'By card', 'byahe-core' ); ?></p><p class="ui-total num"><?php echo esc_html( $f['card'] ); ?></p></div>
</div>
