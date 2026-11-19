<?php
/**
 * Render for byahe/pricing-toggle. Hidden until its script runs, so visitors
 * without JavaScript see monthly prices and no dead switch.
 *
 * @package ByaheCore
 */

defined( 'ABSPATH' ) || exit;

Byahe\Core\pricing_interactivity_state();
?>
<div
	<?php echo get_block_wrapper_attributes( array( 'class' => 'billing-toggle' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	data-wp-interactive="byahe/pricing"
	data-wp-init="callbacks.reveal"
	hidden
>
	<span id="billing-label" class="billing-label"><?php esc_html_e( 'Bill yearly', 'byahe-core' ); ?></span>
	<button
		class="switch"
		type="button"
		role="switch"
		aria-labelledby="billing-label"
		data-wp-bind--aria-checked="state.isYearly"
		data-wp-on--click="actions.toggle"
	></button>
	<span class="pill pill-paid"><?php esc_html_e( '2 months libre', 'byahe-core' ); ?></span>
	<p id="billing-status" class="sr-only" aria-live="polite" data-wp-text="state.status"></p>
</div>
