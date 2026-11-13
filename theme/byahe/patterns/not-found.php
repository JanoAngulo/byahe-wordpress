<?php
/**
 * Title: Page not found
 * Slug: byahe/not-found
 * Inserter: no
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

?>
<!-- wp:group {"tagName":"section","className":"bg-yellow border-b-2 border-navy","layout":{"type":"default"}} -->
<section class="wp-block-group bg-yellow border-b-2 border-navy">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"level":1,"className":"m-0 text-5xl md:text-6xl text-balance max-w-[14ch]"} -->
<h1 class="wp-block-heading m-0 text-5xl md:text-6xl text-balance max-w-[14ch]"><?php esc_html_e( 'Naligaw ka yata.', 'byahe' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-6 mb-0 max-w-[36rem] text-lg"} -->
<p class="mt-6 mb-0 max-w-[36rem] text-lg"><?php esc_html_e( 'This page does not exist. It may have moved, or the link may be wrong.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-8"} -->
<div class="wp-block-buttons mt-8"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'home' ) ); ?>"><?php esc_html_e( 'Back to home', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
