<?php
/**
 * Title: Footer
 * Slug: byahe/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

?>
<!-- wp:separator {"className":"is-style-stripe"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-stripe"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"site-footer__inner","layout":{"type":"default"}} -->
<div class="wp-block-group site-footer__inner">
<!-- wp:group {"className":"site-footer__brand","layout":{"type":"default"}} -->
<div class="wp-block-group site-footer__brand">
<!-- wp:paragraph -->
<p><a class="wordmark wordmark--on-dark" href="<?php echo esc_url( page_url( 'home' ) ); ?>"><span class="wordmark__plate">BYAHE</span><span class="sr-only"> <?php esc_html_e( 'home', 'byahe' ); ?></span></a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Invoices and payments for small businesses across the Philippines.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"nav","ariaLabel":"<?php esc_attr_e( 'Footer', 'byahe' ); ?>","className":"site-footer__nav","layout":{"type":"default"}} -->
<nav class="wp-block-group site-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'byahe' ); ?>">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Product', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( page_url( 'product' ) ); ?>"><?php esc_html_e( 'Features', 'byahe' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( page_url( 'solutions' ) ); ?>"><?php esc_html_e( 'Solutions', 'byahe' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'byahe' ); ?></a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Company', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( page_url( 'about' ) ); ?>"><?php esc_html_e( 'About', 'byahe' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( page_url( 'about' ) . '#credits' ); ?>"><?php esc_html_e( 'Photo credits', 'byahe' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://github.com/JanoAngulo/byahe-wordpress"><?php esc_html_e( 'Code on GitHub', 'byahe' ); ?></a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</nav>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"site-footer__demo"} -->
<p class="site-footer__demo"><?php esc_html_e( 'BYAHE is a fictional company built as a portfolio project. Not a real financial service, and no real payments are processed.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"site-footer__legal"} -->
<p class="site-footer__legal"><?php esc_html_e( '© 2026 BYAHE demo. Made in Bulacan.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
