<?php
/**
 * Render for byahe/demo-dialog. Markup mirrors prototype/partials/demo-dialog.html.
 *
 * @package ByaheCore
 */

defined( 'ABSPATH' ) || exit;
?>
<dialog <?php echo get_block_wrapper_attributes( array( 'class' => 'demo-dialog' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="demo-dialog" aria-labelledby="demo-dialog-title">
	<h2 id="demo-dialog-title"><?php esc_html_e( 'BYAHE is a demo company', 'byahe-core' ); ?></h2>
	<p><?php esc_html_e( 'There’s no real signup. BYAHE was made up for a portfolio project that shows a WordPress build from HTML prototype to block theme. No accounts, no payments, no data collected.', 'byahe-core' ); ?></p>
	<div class="demo-dialog__actions wp-block-buttons">
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://github.com/JanoAngulo/byahe-wordpress"><?php esc_html_e( 'View the code on GitHub', 'byahe-core' ); ?></a></div>
		<div class="wp-block-button is-style-secondary"><button class="wp-block-button__link wp-element-button" type="button" data-close-dialog><?php esc_html_e( 'Close', 'byahe-core' ); ?></button></div>
	</div>
</dialog>
