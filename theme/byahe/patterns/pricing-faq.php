<?php
/**
 * Title: Pricing FAQ
 * Slug: byahe/pricing-faq
 * Categories: byahe, text
 * Description: Questions as native details/summary, with a side card to start on Libre.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

?>
<!-- wp:group {"tagName":"section","anchor":"faq","className":"bg-cream","layout":{"type":"default"}} -->
<section id="faq" class="wp-block-group bg-cream">
<!-- wp:group {"className":"container grid gap-10 py-16 md:py-24 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.75fr)] lg:gap-16 lg:items-start","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-10 py-16 md:py-24 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.75fr)] lg:gap-16 lg:items-start">
<!-- wp:group {"className":"lg:sticky lg:top-8","layout":{"type":"default"}} -->
<div class="wp-block-group lg:sticky lg:top-8">
<!-- wp:heading {"anchor":"faq-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="faq-title"><?php esc_html_e( 'Mga tanong.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 text-lg"} -->
<p class="mt-4 mb-0 text-lg"><?php esc_html_e( 'Questions people ask before they sign up.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"faq-cta card-hard mt-8 p-6","layout":{"type":"default"}} -->
<div class="wp-block-group faq-cta card-hard mt-8 p-6">
<!-- wp:heading {"level":3,"className":"mt-0 mb-2"} -->
<h3 class="wp-block-heading mt-0 mb-2"><?php esc_html_e( 'Still deciding?', 'byahe' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-0 mb-6"} -->
<p class="mt-0 mb-6"><?php esc_html_e( 'Start on Libre. It\'s free for your first 10 invoices a month, and you can upgrade any time.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"opens-demo-dialog"} -->
<div class="wp-block-button opens-demo-dialog"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'about' ) . '#demo-note' ); ?>"><?php esc_html_e( 'Start free', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"faq","layout":{"type":"default"}} -->
<div class="wp-block-group faq">
<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Is Libre really free?', 'byahe' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'Yes. Libre has no monthly fee and no time limit. You pay only the per-payment fee when a customer pays you, the same as on every plan.', 'byahe' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Can I switch plans later?', 'byahe' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'Any time. Upgrades start right away. Downgrades take effect at the end of your current billing period, and you keep your invoices and receipts.', 'byahe' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Do my customers need the BYAHE app?', 'byahe' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'No. They pay from the link or QR code with the bank or e-wallet app they already use, or with a card in their browser.', 'byahe' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'When does the money reach my bank?', 'byahe' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'QR Ph and InstaPay payments settle the same day. Card payments and PESONet settle the next banking day.', 'byahe' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Are the receipts accepted by the BIR?', 'byahe' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'In a real product they would follow BIR receipt rules. BYAHE is a demo company, so nothing here is a valid BIR document.', 'byahe' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary><?php esc_html_e( 'Is BYAHE a real company?', 'byahe' ); ?></summary><!-- wp:paragraph -->
<p><?php esc_html_e( 'No. BYAHE is a fictional company made up for a portfolio project. No accounts, no payments and no data are collected.', 'byahe' ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
