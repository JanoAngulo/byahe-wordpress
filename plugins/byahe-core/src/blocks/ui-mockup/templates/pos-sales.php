<?php
/**
 * UI mockup variant: pos-sales. Markup mirrors prototype/partials/mockups/pos-sales.html.
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
<div class="ui-stats">
	<div class="ui-stat"><p class="ui-muted"><?php esc_html_e( 'Sales today', 'byahe-core' ); ?></p><p class="ui-total num"><?php echo esc_html( $f['sales'] ); ?></p><p class="ui-muted"><span class="num"><?php echo esc_html( $f['payments'] ); ?></span> <?php esc_html_e( 'payments', 'byahe-core' ); ?></p></div>
	<div class="ui-stat"><p class="ui-muted"><?php esc_html_e( 'Suki credit owed', 'byahe-core' ); ?></p><p class="ui-total num"><?php echo esc_html( $f['credit'] ); ?></p><p class="ui-muted"><span class="num"><?php echo esc_html( $f['creditCustomers'] ); ?></span> <?php esc_html_e( 'customers', 'byahe-core' ); ?></p></div>
</div>
<ul class="ui-lines">
<?php foreach ( $l['lines'] as $r ) : ?>
	<li class="ui-row"><span class="ui-muted num"><?php echo esc_html( $r['time'] ); ?></span><span><?php echo esc_html( $r['label'] ); ?></span><span class="num"><?php echo esc_html( $r['amount'] ); ?></span></li>
<?php endforeach; ?>
</ul>
