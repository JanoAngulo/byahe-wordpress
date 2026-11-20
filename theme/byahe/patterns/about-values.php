<?php
/**
 * Title: About: Values
 * Slug: byahe/about-values
 * Categories: byahe, about
 * Description: Four value cards.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_values = array(
	array( __( 'Pesos, not jargon', 'byahe' ), __( 'Prices, fees and reports in plain words and plain pesos. If a sari-sari owner can’t read it, we rewrite it.', 'byahe' ) ),
	array( __( 'Built for the counter', 'byahe' ), __( 'Every feature works on a cheap Android phone, on mobile data, with a line of customers waiting.', 'byahe' ) ),
	array( __( 'Ligtas muna', 'byahe' ), __( 'Safety first. We’d rather ship a week late than ship something that puts someone’s money at risk.', 'byahe' ) ),
	array( __( 'Walang iwanan', 'byahe' ), __( 'No one gets left behind. Free plans stay free, and support answers in English or Tagalog.', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"values","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="values" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"values-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="values-title"><?php esc_html_e( 'What we hold to.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"tagName":"ul","className":"mt-10 mb-0 p-0 list-none grid gap-6 sm:grid-cols-2 xl:grid-cols-4","layout":{"type":"default"}} -->
<ul class="wp-block-group mt-10 mb-0 p-0 list-none grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
<?php foreach ( $byahe_values as $byahe_value ) : ?>
<!-- wp:group {"tagName":"li","className":"card-hard p-6","layout":{"type":"default"}} -->
<li class="wp-block-group card-hard p-6">
<!-- wp:heading {"level":3,"className":"mt-0 mb-2"} -->
<h3 class="wp-block-heading mt-0 mb-2"><?php echo esc_html( $byahe_value[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"m-0"} -->
<p class="m-0"><?php echo esc_html( $byahe_value[1] ); ?></p>
<!-- /wp:paragraph -->
</li>
<!-- /wp:group -->
<?php endforeach; ?>
</ul>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
