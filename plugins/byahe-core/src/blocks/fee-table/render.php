<?php
/**
 * Render for byahe/fee-table. Markup mirrors the fees table on prototype/pricing.html.
 *
 * @package ByaheCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$byahe_caption = (string) ( $attributes['caption'] ?? __( 'Transaction fees', 'byahe-core' ) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'table-scroll' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> tabindex="0" role="region" aria-label="<?php echo esc_attr( $byahe_caption ); ?>">
	<table class="data-table">
		<caption><?php echo esc_html( $byahe_caption ); ?></caption>
		<thead>
			<tr><th scope="col"><?php esc_html_e( 'Payment method', 'byahe-core' ); ?></th><th scope="col"><?php esc_html_e( 'Fee', 'byahe-core' ); ?></th></tr>
		</thead>
		<tbody>
			<?php foreach ( Byahe\Core\pricing()['fees'] as $byahe_fee ) : ?>
				<tr><th scope="row"><?php echo esc_html( $byahe_fee['method'] ); ?></th><td><?php echo esc_html( $byahe_fee['fee'] ); ?></td></tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
