<?php
/**
 * Title: Solutions hero
 * Slug: byahe/solutions-hero
 * Categories: byahe, banner
 * Description: Cream hero with links to each industry section.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_jumps = array(
	'retail'         => __( 'Sari-sari and retail', 'byahe' ),
	'food'           => __( 'Food and cafés', 'byahe' ),
	'freelancers'    => __( 'Freelancers and agencies', 'byahe' ),
	'online-sellers' => __( 'Online sellers', 'byahe' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"solutions-hero","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="solutions-hero" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container pt-12 pb-16 md:pt-16 md:pb-20","layout":{"type":"default"}} -->
<div class="wp-block-group container pt-12 pb-16 md:pt-16 md:pb-20">
<!-- wp:heading {"level":1,"anchor":"solutions-title","className":"m-0 text-5xl md:text-6xl text-balance max-w-[14ch]"} -->
<h1 class="wp-block-heading m-0 text-5xl md:text-6xl text-balance max-w-[14ch]" id="solutions-title"><span class="whitespace-nowrap"><?php esc_html_e( 'Para sa', 'byahe' ); ?></span> <span class="md:whitespace-nowrap"><?php esc_html_e( 'bawat negosyo.', 'byahe' ); ?></span></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-6 mb-0 max-w-[40rem] text-lg"} -->
<p class="mt-6 mb-0 max-w-[40rem] text-lg"><?php esc_html_e( 'For every kind of business. A sari-sari store, a café, a two-person studio and an online shop all get paid differently. Here’s how BYAHE fits each one.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"tagName":"nav","ariaLabel":"<?php esc_attr_e( 'Solutions', 'byahe' ); ?>","layout":{"type":"default"}} -->
<nav class="wp-block-group" aria-label="<?php esc_attr_e( 'Solutions', 'byahe' ); ?>">
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
