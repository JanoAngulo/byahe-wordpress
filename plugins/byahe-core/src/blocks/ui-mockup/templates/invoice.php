<?php
/**
 * UI mockup variant: invoice. Markup mirrors prototype/partials/mockups/invoice.html.
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
	<p class="ui-title"><?php esc_html_e( 'Invoice', 'byahe-core' ); ?> <span class="num"><?php echo esc_html( $f['number'] ); ?></span></p>
	<span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $f['status'] ) ); ?>"><?php echo esc_html( $f['status'] ); ?></span>
</div>
<div class="ui-row"><span class="ui-muted"><?php esc_html_e( 'Bill to', 'byahe-core' ); ?></span><span class="ui-strong"><?php echo esc_html( $f['billTo'] ); ?></span></div>
<div class="ui-row"><span class="ui-muted"><?php esc_html_e( 'Due', 'byahe-core' ); ?></span><span class="ui-strong"><?php echo esc_html( $f['due'] ); ?></span></div>
<ul class="ui-lines">
<?php foreach ( $l['lines'] as $r ) : ?>
	<li class="ui-row"><span class="num"><?php echo esc_html( $r['qty'] ); ?></span><span><?php echo esc_html( $r['item'] ); ?></span><span class="num"><?php echo esc_html( $r['amount'] ); ?></span></li>
<?php endforeach; ?>
</ul>
<div class="ui-sum">
	<div class="ui-row"><span class="ui-muted"><?php esc_html_e( 'Subtotal', 'byahe-core' ); ?></span><span class="num"><?php echo esc_html( $f['subtotal'] ); ?></span></div>
	<div class="ui-row"><span class="ui-muted"><?php echo esc_html( $f['vatLabel'] ); ?></span><span class="num"><?php echo esc_html( $f['vat'] ); ?></span></div>
	<div class="ui-row"><span class="ui-strong"><?php esc_html_e( 'Total', 'byahe-core' ); ?></span><span class="ui-total num"><?php echo esc_html( $f['total'] ); ?></span></div>
</div>
<div class="ui-actions"><span class="ui-btn ui-btn--primary"><?php esc_html_e( 'Send by SMS', 'byahe-core' ); ?></span><span class="ui-btn"><?php esc_html_e( 'Email', 'byahe-core' ); ?></span><span class="ui-btn"><?php esc_html_e( 'Copy link', 'byahe-core' ); ?></span></div>
