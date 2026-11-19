<?php
/**
 * UI mockup variant: bir-receipt. Markup mirrors prototype/partials/mockups/bir-receipt.html.
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
	<p class="ui-title"><?php esc_html_e( 'Receipt', 'byahe-core' ); ?> <span class="num"><?php echo esc_html( $f['number'] ); ?></span></p>
	<span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $f['status'] ) ); ?>"><?php echo esc_html( $f['status'] ); ?></span>
</div>
<div class="ui-receipt-layout">
	<div class="ui-receipt">
		<div class="ui-receipt-head">
			<p class="ui-strong"><?php esc_html_e( 'Official receipt', 'byahe-core' ); ?></p>
			<p><?php echo esc_html( $f['business'] ); ?></p>
			<p class="ui-muted"><?php echo esc_html( $f['address'] ); ?></p>
			<p class="ui-muted"><?php esc_html_e( 'TIN', 'byahe-core' ); ?> <span class="num"><?php echo esc_html( $f['tin'] ); ?></span></p>
		</div>
		<div class="ui-receipt-body">
			<div class="ui-row"><span><?php esc_html_e( 'Date', 'byahe-core' ); ?></span><span><?php echo esc_html( $f['date'] ); ?></span></div>
			<div class="ui-row"><span><?php esc_html_e( 'Paid by', 'byahe-core' ); ?></span><span><?php echo esc_html( $f['paidBy'] ); ?></span></div>
			<div class="ui-row"><span><?php esc_html_e( 'VATable sales', 'byahe-core' ); ?></span><span class="num"><?php echo esc_html( $f['vatable'] ); ?></span></div>
			<div class="ui-row"><span><?php esc_html_e( 'VAT 12%', 'byahe-core' ); ?></span><span class="num"><?php echo esc_html( $f['vat'] ); ?></span></div>
			<div class="ui-row ui-strong"><span><?php esc_html_e( 'Total', 'byahe-core' ); ?></span><span class="num"><?php echo esc_html( $f['total'] ); ?></span></div>
		</div>
		<p class="ui-receipt-foot ui-muted"><?php echo esc_html( $f['note'] ); ?></p>
	</div>
	<ul class="ui-checklist">
<?php foreach ( $l['checklist'] as $r ) : ?>
		<li><p class="ui-strong"><?php echo esc_html( $r['title'] ); ?></p><p class="ui-muted"><?php echo esc_html( $r['detail'] ); ?></p></li>
<?php endforeach; ?>
	</ul>
</div>
