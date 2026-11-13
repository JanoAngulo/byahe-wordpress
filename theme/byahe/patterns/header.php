<?php
/**
 * Title: Header
 * Slug: byahe/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package Byahe
 */

use function Byahe\Theme\nav_link_attrs;
use function Byahe\Theme\page_url;

?>
<!-- wp:group {"className":"site-header__inner","layout":{"type":"default"}} -->
<div class="wp-block-group site-header__inner">
<!-- wp:paragraph {"className":"site-header__brand"} -->
<p class="site-header__brand"><a class="wordmark" href="<?php echo esc_url( page_url( 'home' ) ); ?>"><span class="wordmark__plate">BYAHE</span><span class="sr-only"> <?php esc_html_e( 'home', 'byahe' ); ?></span></a></p>
<!-- /wp:paragraph -->

<!-- wp:navigation {"overlayMenu":"mobile","ariaLabel":"<?php esc_attr_e( 'Primary', 'byahe' ); ?>","className":"primary-nav","overlayBackgroundColor":"cream","overlayTextColor":"navy","layout":{"type":"flex","justifyContent":"right"}} -->
<!-- wp:navigation-link <?php echo nav_link_attrs( 'product', __( 'Product', 'byahe' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON block attributes. ?> /-->
<!-- wp:navigation-link <?php echo nav_link_attrs( 'solutions', __( 'Solutions', 'byahe' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- wp:navigation-link <?php echo nav_link_attrs( 'pricing', __( 'Pricing', 'byahe' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- wp:navigation-link <?php echo nav_link_attrs( 'about', __( 'About', 'byahe' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- /wp:navigation -->

<!-- wp:buttons {"className":"header-cta-wrap"} -->
<div class="wp-block-buttons header-cta-wrap"><!-- wp:button {"className":"header-cta"} -->
<div class="wp-block-button header-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'pricing' ) . '#libre' ); ?>"><?php esc_html_e( 'Sakay na →', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
