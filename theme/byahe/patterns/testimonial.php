<?php
/**
 * Title: Testimonial
 * Slug: byahe/testimonial
 * Categories: byahe, testimonials
 * Description: One large quote from a fictional customer, with an initials avatar.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

?>
<!-- wp:group {"tagName":"section","anchor":"testimonial","className":"bg-blue text-white border-b-2 border-navy on-dark","layout":{"type":"default"}} -->
<section id="testimonial" class="wp-block-group bg-blue text-white border-b-2 border-navy on-dark">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"testimonial-title","className":"sr-only"} -->
<h2 class="wp-block-heading sr-only" id="testimonial-title"><?php esc_html_e( 'What a customer says', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"tagName":"figure","className":"m-0 max-w-[52rem]","layout":{"type":"default"}} -->
<figure class="wp-block-group m-0 max-w-[52rem]">
<!-- wp:quote {"className":"m-0"} -->
<blockquote class="wp-block-quote m-0"><!-- wp:paragraph {"className":"m-0 font-heading font-bold text-yellow text-3xl"} -->
<p class="m-0 font-heading font-bold text-yellow text-3xl"><?php esc_html_e( '“I used to chase suki for weeks over Messenger. Now they scan the QR at the counter and I see it in BYAHE before they’ve even left.”', 'byahe' ); ?></p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:group {"tagName":"figcaption","className":"mt-8 flex items-center gap-4","layout":{"type":"default"}} -->
<figcaption class="wp-block-group mt-8 flex items-center gap-4">
<!-- wp:paragraph {"className":"avatar m-0"} -->
<p class="avatar m-0">MR</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"m-0"} -->
<p class="m-0"><strong><?php esc_html_e( 'Maricel Reyes', 'byahe' ); ?></strong><br><?php esc_html_e( "Owner, Kape't Tinapay Co., Malolos", 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</figcaption>
<!-- /wp:group -->
</figure>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
