<?php
/**
 * Title: Product hero
 * Slug: byahe/product-hero
 * Categories: byahe, banner
 * Description: Blue hero with jump links to each feature section.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_jumps = array(
	'invoicing'     => __( 'Invoicing', 'byahe' ),
	'payment-links' => __( 'Payment links and QR', 'byahe' ),
	'reminders'     => __( 'Auto reminders', 'byahe' ),
	'cash-flow'     => __( 'Cash flow', 'byahe' ),
	'bir-receipts'  => __( 'BIR-ready receipts', 'byahe' ),
	'team-access'   => __( 'Team access', 'byahe' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"product-hero","className":"bg-blue text-white border-b-2 border-navy on-dark","layout":{"type":"default"}} -->
<section id="product-hero" class="wp-block-group bg-blue text-white border-b-2 border-navy on-dark">
<!-- wp:group {"className":"container pt-12 pb-16 md:pt-16 md:pb-24","layout":{"type":"default"}} -->
<div class="wp-block-group container pt-12 pb-16 md:pt-16 md:pb-24">
<!-- wp:heading {"level":1,"anchor":"product-title","className":"m-0 text-5xl md:text-6xl text-yellow text-balance max-w-[14ch]"} -->
<h1 class="wp-block-heading m-0 text-5xl md:text-6xl text-yellow text-balance max-w-[14ch]" id="product-title"><span class="whitespace-nowrap"><?php esc_html_e( 'Singil na,', 'byahe' ); ?></span> <span class="whitespace-nowrap"><?php esc_html_e( 'bayad agad.', 'byahe' ); ?></span></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-6 mb-0 max-w-[40rem] text-lg"} -->
<p class="mt-6 mb-0 max-w-[40rem] text-lg"><?php esc_html_e( 'Six tools that take you from “here’s your bill” to money in the bank, built for how Filipino small businesses actually get paid.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"tagName":"nav","ariaLabel":"<?php esc_attr_e( 'Jump to a feature', 'byahe' ); ?>","layout":{"type":"default"}} -->
<nav class="wp-block-group" aria-label="<?php esc_attr_e( 'Jump to a feature', 'byahe' ); ?>">
<!-- wp:list {"className":"feature-jump"} -->
<ul class="wp-block-list feature-jump">
<?php foreach ( $byahe_jumps as $byahe_id => $byahe_label ) : ?>
<!-- wp:list-item -->
<li><a href="#<?php echo esc_attr( $byahe_id ); ?>"><?php echo esc_html( $byahe_label ); ?></a></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</nav>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
