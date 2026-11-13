<?php
/**
 * Title: Search results
 * Slug: byahe/search-results
 * Inserter: no
 *
 * @package Byahe
 */

?>
<!-- wp:group {"tagName":"section","className":"bg-cream","layout":{"type":"default"}} -->
<section class="wp-block-group bg-cream">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:query-title {"type":"search","level":1,"className":"m-0 text-5xl"} /-->

<!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'byahe' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'byahe' ); ?>","className":"mt-8"} /-->

<!-- wp:query {"queryId":1,"query":{"inherit":true,"perPage":10},"className":"mt-10"} -->
<div class="wp-block-query mt-10"><!-- wp:post-template -->
<!-- wp:post-title {"level":2,"isLink":true,"className":"text-2xl"} /-->

<!-- wp:post-excerpt /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Wala pa. Nothing matched that search. Try another word.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
