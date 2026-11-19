<?php
/**
 * Title: Security strip
 * Slug: byahe/security-strip
 * Categories: byahe, featured
 * Description: Three security cards on blue. The BSP claim is marked as a demo.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_cards = array(
	array( 'lock', __( 'Bank-grade encryption', 'byahe' ), __( '256-bit encryption in transit and at rest.', 'byahe' ) ),
	array( 'shield-check', __( 'PCI DSS card security', 'byahe' ), __( 'Card numbers never touch your device or ours.', 'byahe' ) ),
	array( 'landmark', __( 'BSP-registered*', 'byahe' ), __( '*Demo company. BYAHE is not actually registered with the Bangko Sentral ng Pilipinas.', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"security","className":"bg-blue text-white border-y-2 border-navy on-dark","layout":{"type":"default"}} -->
<section id="security" class="wp-block-group bg-blue text-white border-y-2 border-navy on-dark">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"security-title","className":"m-0 text-yellow text-4xl"} -->
<h2 class="wp-block-heading m-0 text-yellow text-4xl" id="security-title"><?php esc_html_e( 'Ligtas ang pera mo.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[40rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[40rem] text-lg"><?php esc_html_e( "Your money and your customers' details get the same protection a bank uses.", 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"tagName":"ul","className":"cards-3 mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3","layout":{"type":"default"}} -->
<ul class="wp-block-group cards-3 mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3">
<?php foreach ( $byahe_cards as $byahe_card ) : ?>
<!-- wp:group {"tagName":"li","className":"card-hard feature-card","layout":{"type":"default"}} -->
<li class="wp-block-group card-hard feature-card">
<!-- wp:byahe/icon {"icon":"<?php echo esc_attr( $byahe_card[0] ); ?>","className":"card-sign"} /-->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body">
<!-- wp:heading {"level":3,"className":"mt-0 mb-2 text-xl"} -->
<h3 class="wp-block-heading mt-0 mb-2 text-xl"><?php echo esc_html( $byahe_card[1] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"m-0"} -->
<p class="m-0"><?php echo esc_html( $byahe_card[2] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</li>
<!-- /wp:group -->
<?php endforeach; ?>
</ul>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
