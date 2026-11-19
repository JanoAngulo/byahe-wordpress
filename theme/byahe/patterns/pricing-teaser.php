<?php
/**
 * Title: Pricing teaser
 * Slug: byahe/pricing-teaser
 * Categories: byahe, call-to-action
 * Description: The three paid plans in compact cards. Names and prices come from the BYAHE pricing data.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

/**
 * Paragraph or heading attributes bound to one pricing field.
 *
 * @param string $tier  Tier slug.
 * @param string $field Field name.
 * @param array  $extra Other block attributes.
 */
$byahe_bound = static function ( string $tier, string $field, array $extra = array() ): string {
	$attrs = array_merge(
		array(
			'metadata' => array(
				'bindings' => array(
					'content' => array(
						'source' => 'byahe/pricing',
						'args'   => array(
							'tier'  => $tier,
							'field' => $field,
						),
					),
				),
			),
		),
		$extra
	);
	return (string) wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
};

$byahe_tiers = array(
	array(
		'tier'     => 'negosyo',
		'name'     => __( 'Negosyo', 'byahe' ),
		'price'    => '₱499',
		'period'   => __( 'per month', 'byahe' ),
		'desc'     => __( 'Unlimited invoices and automatic reminders.', 'byahe' ),
		'featured' => true,
	),
	array(
		'tier'     => 'pro',
		'name'     => __( 'Pro', 'byahe' ),
		'price'    => '₱1,499',
		'period'   => __( 'per month', 'byahe' ),
		'desc'     => __( 'Team access, the cash-flow dashboard and the API.', 'byahe' ),
		'featured' => false,
	),
	array(
		'tier'     => 'enterprise',
		'name'     => __( 'Enterprise', 'byahe' ),
		'price'    => __( "Let's talk", 'byahe' ),
		'period'   => __( 'custom pricing', 'byahe' ),
		'desc'     => __( 'Volume pricing, custom roles and a dedicated contact.', 'byahe' ),
		'featured' => false,
	),
);
?>
<!-- wp:group {"tagName":"section","anchor":"pricing-teaser","className":"bg-cream","layout":{"type":"default"}} -->
<section id="pricing-teaser" class="wp-block-group bg-cream">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"pricing-teaser-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="pricing-teaser-title"><?php esc_html_e( 'Simple pricing in pesos.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 text-lg"} -->
<p class="mt-4 mb-0 text-lg"><?php esc_html_e( 'Start on Libre, free for up to 10 invoices a month. Upgrade when you outgrow it.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"tagName":"ul","className":"mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3","layout":{"type":"default"}} -->
<ul class="wp-block-group mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3">
<?php
foreach ( $byahe_tiers as $byahe_t ) :
	$byahe_card     = 'card-hard tier tier--teaser' . ( $byahe_t['featured'] ? ' card-hard-lg bg-yellow!' : '' );
	$byahe_price    = 'enterprise' === $byahe_t['tier'] ? 'price-text' : 'price num';
	$byahe_name_a   = $byahe_bound( $byahe_t['tier'], 'name', array( 'level' => 3, 'className' => 'tier__name' ) ); // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
	$byahe_price_a  = $byahe_bound( $byahe_t['tier'], 'price', array( 'className' => $byahe_price ) );
	$byahe_period_a = $byahe_bound( $byahe_t['tier'], 'period', array( 'className' => 'price-period' ) );
	$byahe_badge_a  = $byahe_bound( $byahe_t['tier'], 'badge', array( 'className' => 'pill pill-pending tier__badge' ) );
	$byahe_button   = $byahe_t['featured'] ? '' : ' is-style-secondary';
	// Block attributes are JSON built by wp_json_encode(); escaping would break them.
	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
<!-- wp:group {"tagName":"li","className":"<?php echo esc_attr( $byahe_card ); ?>","layout":{"type":"default"}} -->
<li class="wp-block-group <?php echo esc_attr( $byahe_card ); ?>">
<!-- wp:group {"className":"tier__top","layout":{"type":"default"}} -->
<div class="wp-block-group tier__top">
<!-- wp:heading <?php echo $byahe_name_a; ?> -->
<h3 class="wp-block-heading tier__name"><?php echo esc_html( $byahe_t['name'] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:group {"className":"tier__price","layout":{"type":"default"}} -->
<div class="wp-block-group tier__price">
<!-- wp:paragraph <?php echo $byahe_price_a; ?> -->
<p class="<?php echo esc_attr( $byahe_price ); ?>"><?php echo esc_html( $byahe_t['price'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph <?php echo $byahe_period_a; ?> -->
<p class="price-period"><?php echo esc_html( $byahe_t['period'] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
	<?php if ( $byahe_t['featured'] ) : ?>
<!-- wp:paragraph <?php echo $byahe_badge_a; ?> -->
<p class="pill pill-pending tier__badge"><?php esc_html_e( 'Most popular', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
	<?php endif; ?>

<!-- wp:group {"className":"tier__bottom","layout":{"type":"default"}} -->
<div class="wp-block-group tier__bottom">
<!-- wp:paragraph {"className":"tier__desc"} -->
<p class="tier__desc"><?php echo esc_html( $byahe_t['desc'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button <?php echo $byahe_button ? '{"className":"is-style-secondary"} ' : ''; ?>-->
<div class="wp-block-button<?php echo esc_attr( $byahe_button ); ?>"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'Compare plans', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</li>
<!-- /wp:group -->
	<?php
	// phpcs:enable
endforeach;
?>
</ul>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
