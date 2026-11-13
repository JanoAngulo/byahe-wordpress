<?php
/**
 * Render for byahe/hero-dashboard. Markup matches the prototype partial
 * prototype/partials/mockups/hero-dashboard.html.
 *
 * @package ByaheCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$byahe_data = Byahe\Core\resolve_fields( Byahe\Core\schema( 'hero-dashboard' ), (array) ( $attributes['values'] ?? array() ) );
$byahe_f    = $byahe_data['fields'];
$byahe_bars = $byahe_data['lists']['bars'];
$byahe_last = count( $byahe_bars ) - 1;
$byahe_pill = array(
	'Paid'    => 'pill-paid',
	'Pending' => 'pill-pending',
	'Overdue' => 'pill-overdue',
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'hero-dashboard card-hard card-hard-lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="img" aria-label="<?php echo esc_attr( $byahe_data['aria'] ); ?>">
	<div class="hero-dashboard__top">
		<div>
			<p class="hero-dashboard__label"><?php echo esc_html( $byahe_f['label'] ); ?></p>
			<p class="hero-dashboard__total num"><?php echo esc_html( $byahe_f['total'] ); ?></p>
			<p class="hero-dashboard__delta"><span class="num"><?php echo esc_html( $byahe_f['delta'] ); ?></span> <?php echo esc_html( $byahe_f['deltaLabel'] ); ?></p>
		</div>
		<div class="hero-dashboard__chart" aria-hidden="true">
			<?php foreach ( $byahe_bars as $byahe_i => $byahe_bar ) : ?>
				<span<?php echo $byahe_i === $byahe_last ? ' class="is-current"' : ''; ?> style="--h: <?php echo esc_attr( (string) min( 100, max( 0, (int) $byahe_bar['height'] ) ) ); ?>%"></span>
			<?php endforeach; ?>
		</div>
	</div>
	<ul class="hero-dashboard__list">
		<?php foreach ( $byahe_data['lists']['invoices'] as $byahe_row ) : ?>
			<li>
				<span class="hero-dashboard__who"><span class="hero-dashboard__inv num"><?php echo esc_html( $byahe_row['number'] ); ?></span><?php echo esc_html( $byahe_row['customer'] ); ?></span>
				<span class="num"><?php echo esc_html( $byahe_row['amount'] ); ?></span>
				<span class="pill <?php echo esc_attr( $byahe_pill[ $byahe_row['status'] ] ?? 'pill-pending' ); ?>"><?php echo esc_html( $byahe_row['status'] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
