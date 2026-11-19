<?php
/**
 * UI mockup variant: cashflow-chart. Markup mirrors prototype/partials/mockups/cashflow-chart.html.
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
	<span class="ui-muted"><?php echo esc_html( $f['period'] ); ?></span>
</div>
<p class="ui-legend"><span class="is-in"><?php esc_html_e( 'Money in', 'byahe-core' ); ?></span><span class="is-out"><?php esc_html_e( 'Money out', 'byahe-core' ); ?></span></p>
<div class="ui-bars" aria-hidden="true">
<?php foreach ( $l['months'] as $r ) : ?>
	<div><i class="in" style="--h: <?php echo esc_attr( (string) min( 100, max( 0, (int) $r['in'] ) ) ); ?>%"></i><i class="out" style="--h: <?php echo esc_attr( (string) min( 100, max( 0, (int) $r['out'] ) ) ); ?>%"></i></div>
<?php endforeach; ?>
</div>
<p class="ui-bars-labels ui-muted" aria-hidden="true">
<?php foreach ( $l['months'] as $r ) : ?>
	<span><?php echo esc_html( $r['month'] ); ?></span>
<?php endforeach; ?>
</p>
<div class="ui-stats">
	<div class="ui-stat"><p class="ui-muted"><?php esc_html_e( 'Expected this week', 'byahe-core' ); ?></p><p class="ui-total num"><?php echo esc_html( $f['expected'] ); ?></p></div>
	<div class="ui-stat"><p class="ui-muted"><?php esc_html_e( 'Overdue', 'byahe-core' ); ?></p><p class="ui-total"><span class="num"><?php echo esc_html( $f['overdue'] ); ?></span> <?php esc_html_e( 'invoices', 'byahe-core' ); ?></p></div>
</div>
