<?php
/**
 * Title: CTA band
 * Slug: byahe/cta-band
 * Categories: byahe, call-to-action
 * Description: Blue closing band with a yellow headline and the Sakay na button.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

?>
<!-- wp:group {"tagName":"section","anchor":"cta","className":"cta-band on-dark","layout":{"type":"default"}} -->
<section id="cta" class="wp-block-group cta-band on-dark">
<!-- wp:group {"className":"cta-band__inner","layout":{"type":"default"}} -->
<div class="wp-block-group cta-band__inner">
<!-- wp:heading {"anchor":"cta-band-title"} -->
<h2 class="wp-block-heading" id="cta-band-title"><?php esc_html_e( 'Tara na. Your first 10 invoices are on us.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Set up in a few minutes. No card needed.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-accent"} -->
<div class="wp-block-button is-style-accent"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'pricing' ) . '#libre' ); ?>"><?php esc_html_e( 'Sakay na →', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
