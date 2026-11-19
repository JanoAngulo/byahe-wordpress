<?php
/**
 * Render for byahe/pricing-card. Name, price and period come from the
 * pricing data; the price swaps with the billing toggle through the
 * byahe/pricing Interactivity store.
 *
 * @package ByaheCore
 *
 * @var array  $attributes Block attributes.
 * @var string $content    Inner blocks (description, list, button).
 */

defined( 'ABSPATH' ) || exit;

$byahe_tier = (string) ( $attributes['tier'] ?? '' );
if ( ! isset( Byahe\Core\pricing()['tiers'][ $byahe_tier ] ) ) {
	return;
}

Byahe\Core\pricing_interactivity_state();

$byahe_badge   = Byahe\Core\tier_field( $byahe_tier, 'badge' );
$byahe_is_text = null === Byahe\Core\pricing()['tiers'][ $byahe_tier ]['monthly'];
$byahe_context = array(
	'monthly'       => Byahe\Core\tier_field( $byahe_tier, 'price', 'monthly' ),
	'yearly'        => Byahe\Core\tier_field( $byahe_tier, 'price', 'yearly' ),
	'periodMonthly' => Byahe\Core\tier_field( $byahe_tier, 'period', 'monthly' ),
	'periodYearly'  => Byahe\Core\tier_field( $byahe_tier, 'period', 'yearly' ),
);
$byahe_classes = 'card-hard tier' . ( empty( $attributes['featured'] ) ? '' : ' card-hard-lg bg-yellow!' );
$byahe_wrapper = get_block_wrapper_attributes(
	array(
		'class' => $byahe_classes,
		'id'    => $byahe_tier,
	)
);
?>
<div <?php echo $byahe_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="listitem" data-wp-interactive="byahe/pricing" <?php echo wp_interactivity_data_wp_context( $byahe_context ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h3 class="tier__name"><?php echo esc_html( Byahe\Core\tier_field( $byahe_tier, 'name' ) ); ?></h3>
	<?php if ( $byahe_badge ) : ?>
		<span class="pill pill-pending tier__badge"><?php echo esc_html( $byahe_badge ); ?></span>
	<?php endif; ?>
	<p class="tier__price">
		<?php if ( $byahe_is_text ) : ?>
			<span class="price-text"><?php echo esc_html( $byahe_context['monthly'] ); ?></span>
			<span class="price-period"><?php echo esc_html( $byahe_context['periodMonthly'] ); ?></span>
		<?php else : ?>
			<span class="price num" data-wp-text="state.price"><?php echo esc_html( $byahe_context['monthly'] ); ?></span>
			<span class="price-period" data-wp-text="state.period"><?php echo esc_html( $byahe_context['periodMonthly'] ); ?></span>
		<?php endif; ?>
	</p>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered inner blocks. ?>
</div>
