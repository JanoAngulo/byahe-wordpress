<?php
/**
 * UI mockup variant: order-links. Markup mirrors prototype/partials/mockups/order-links.html.
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
	<span class="ui-btn ui-btn--primary"><?php echo esc_html( $f['button'] ); ?></span>
</div>
<?php foreach ( $l['orders'] as $r ) : ?>
<div class="ui-row"><span><span class="ui-strong num"><?php echo esc_html( $r['number'] ); ?></span><br><span class="ui-muted"><?php echo esc_html( $r['item'] ); ?></span></span><span class="num ui-strong"><?php echo esc_html( $r['amount'] ); ?></span><span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $r['status'] ) ); ?>"><?php echo esc_html( $r['status'] ); ?></span></div>
<?php endforeach; ?>
<div class="ui-actions"><span class="ui-btn"><?php esc_html_e( 'Copy link', 'byahe-core' ); ?></span><span class="ui-btn"><?php esc_html_e( 'Send in Messenger', 'byahe-core' ); ?></span><span class="ui-btn"><?php esc_html_e( 'Resend expired', 'byahe-core' ); ?></span></div>
