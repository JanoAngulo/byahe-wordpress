<?php
/**
 * Title: Pricing fees
 * Slug: byahe/pricing-fees
 * Categories: byahe, text
 * Description: Per-payment fees, read from the BYAHE pricing data.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

?>
<!-- wp:group {"tagName":"section","anchor":"fees","className":"bg-yellow border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="fees" class="wp-block-group bg-yellow border-b-2 border-navy">
<!-- wp:group {"className":"container grid gap-10 py-16 md:py-24 lg:grid-cols-[1fr_1.2fr] lg:items-start","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-10 py-16 md:py-24 lg:grid-cols-[1fr_1.2fr] lg:items-start">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"anchor":"fees-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="fees-title"><?php esc_html_e( 'Fees per payment.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[32rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[32rem] text-lg"><?php esc_html_e( 'Same on every plan. You only pay when you get paid, and the fee shows on every transaction.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:byahe/fee-table /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
