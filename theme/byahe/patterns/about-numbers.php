<?php
/**
 * Title: About: By the numbers
 * Slug: byahe/about-numbers
 * Categories: byahe, about
 * Description: Four demo figures on blue.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_stats = array(
	array( '2,400', __( 'small businesses', 'byahe' ) ),
	array( '₱1.2B', __( 'paid through BYAHE', 'byahe' ) ),
	array( '6', __( 'days faster to get paid, on average', 'byahe' ) ),
	array( '12', __( 'people on the team', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"numbers","className":"bg-blue text-white border-b-2 border-navy on-dark","layout":{"type":"default"}} -->
<section id="numbers" class="wp-block-group bg-blue text-white border-b-2 border-navy on-dark">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"numbers-title","className":"m-0 text-4xl text-yellow"} -->
<h2 class="wp-block-heading m-0 text-4xl text-yellow" id="numbers-title"><?php esc_html_e( 'By the numbers.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 text-lg"} -->
<p class="mt-4 mb-0 text-lg"><?php esc_html_e( 'Demo figures, made up for this portfolio project.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"stats"} -->
<ul class="wp-block-list stats">
<?php foreach ( $byahe_stats as $byahe_stat ) : ?>
<!-- wp:list-item -->
<li><span class="num"><?php echo esc_html( $byahe_stat[0] ); ?></span><?php echo esc_html( $byahe_stat[1] ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
