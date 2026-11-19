<?php
/**
 * UI mockup variant: retainer-invoice. Markup mirrors prototype/partials/mockups/retainer-invoice.html.
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
	<span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $f['status'] ) ); ?>"><?php echo esc_html( $f['status'] ); ?></span>
</div>
<div class="ui-row"><span class="ui-muted"><?php esc_html_e( 'Every month', 'byahe-core' ); ?></span><span class="ui-total num"><?php echo esc_html( $f['amount'] ); ?></span></div>
<div class="ui-row"><span class="ui-muted"><?php esc_html_e( 'Sends on', 'byahe-core' ); ?></span><span class="ui-strong"><?php echo esc_html( $f['sendsOn'] ); ?></span></div>
<div class="ui-row"><span class="ui-muted"><?php esc_html_e( 'Next invoice', 'byahe-core' ); ?></span><span class="ui-strong"><?php echo esc_html( $f['next'] ); ?></span></div>
<p class="ui-strong ui-subhead"><?php echo esc_html( $f['subhead'] ); ?></p>
<?php foreach ( $l['milestones'] as $r ) : ?>
<div class="ui-row"><span><?php echo esc_html( $r['name'] ); ?></span><span class="num"><?php echo esc_html( $r['amount'] ); ?></span><span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $r['status'] ) ); ?>"><?php echo esc_html( $r['status'] ); ?></span></div>
<?php endforeach; ?>
