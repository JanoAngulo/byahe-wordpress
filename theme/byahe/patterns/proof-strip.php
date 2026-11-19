<?php
/**
 * Title: Customer strip
 * Slug: byahe/proof-strip
 * Categories: byahe, testimonials
 * Description: Fictional customer names as route plates.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_customers = array(
	__( "Kape't Tinapay Co.", 'byahe' ),
	__( "Aling Nena's Sari-Sari", 'byahe' ),
	__( 'Studio Bahaghari', 'byahe' ),
	__( 'Lakbay Prints', 'byahe' ),
	__( 'Tindahan ni Mang Oca', 'byahe' ),
	__( 'Bulaklak Online', 'byahe' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"proof","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="proof" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container py-10","layout":{"type":"default"}} -->
<div class="wp-block-group container py-10">
<!-- wp:heading {"anchor":"proof-title","className":"m-0 text-xl"} -->
<h2 class="wp-block-heading m-0 text-xl" id="proof-title"><?php esc_html_e( 'Ka-byahe from Bulacan to Davao', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"proof-list mt-6 mb-0 p-0 list-none flex flex-wrap gap-3"} -->
<ul class="wp-block-list proof-list mt-6 mb-0 p-0 list-none flex flex-wrap gap-3">
<?php foreach ( $byahe_customers as $byahe_name ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $byahe_name ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph {"className":"mt-4 mb-0 text-sm"} -->
<p class="mt-4 mb-0 text-sm"><?php esc_html_e( 'Customer names are made up for this demo.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
