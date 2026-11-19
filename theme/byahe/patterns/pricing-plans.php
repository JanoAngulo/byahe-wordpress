<?php
/**
 * Title: Pricing plans
 * Slug: byahe/pricing-plans
 * Categories: byahe, call-to-action
 * Description: Billing toggle and the four plan cards. Prices come from the BYAHE pricing data.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

?>
<!-- wp:group {"tagName":"section","anchor":"plans","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="plans" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:group {"className":"flex flex-wrap items-end justify-between gap-6","layout":{"type":"default"}} -->
<div class="wp-block-group flex flex-wrap items-end justify-between gap-6">
<!-- wp:heading {"anchor":"plans-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="plans-title"><?php esc_html_e( 'Pick your plan.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:byahe/pricing-toggle /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"tier-list mt-10 mb-0 grid gap-6 md:grid-cols-2 xl:grid-cols-4","layout":{"type":"default"}} -->
<div class="wp-block-group tier-list mt-10 mb-0 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
<!-- wp:byahe/pricing-card {"tier":"libre"} -->
<!-- wp:paragraph {"className":"tier__desc"} -->
<p class="tier__desc"><?php esc_html_e( 'For trying BYAHE or a side hustle.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"tier__list"} -->
<ul class="wp-block-list tier__list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Up to 10 invoices a month', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Payment links and QR Ph', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'BIR-ready receipts', 'byahe' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secondary opens-demo-dialog"} -->
<div class="wp-block-button is-style-secondary opens-demo-dialog"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'about' ) . '#demo-note' ); ?>"><?php esc_html_e( 'Start free', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:byahe/pricing-card -->

<!-- wp:byahe/pricing-card {"tier":"negosyo","featured":true} -->
<!-- wp:paragraph {"className":"tier__desc"} -->
<p class="tier__desc"><?php esc_html_e( 'For shops and cafés that invoice every day.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"tier__list"} -->
<ul class="wp-block-list tier__list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Unlimited invoices', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Automatic reminders', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Everything in Libre', 'byahe' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"opens-demo-dialog"} -->
<div class="wp-block-button opens-demo-dialog"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'about' ) . '#demo-note' ); ?>"><?php esc_html_e( 'Choose Negosyo', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:byahe/pricing-card -->

<!-- wp:byahe/pricing-card {"tier":"pro"} -->
<!-- wp:paragraph {"className":"tier__desc"} -->
<p class="tier__desc"><?php esc_html_e( 'For teams with a cashier and a bookkeeper.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"tier__list"} -->
<ul class="wp-block-list tier__list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Up to 10 team members', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Cash-flow dashboard', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Webhooks and API', 'byahe' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secondary opens-demo-dialog"} -->
<div class="wp-block-button is-style-secondary opens-demo-dialog"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'about' ) . '#demo-note' ); ?>"><?php esc_html_e( 'Choose Pro', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:byahe/pricing-card -->

<!-- wp:byahe/pricing-card {"tier":"enterprise"} -->
<!-- wp:paragraph {"className":"tier__desc"} -->
<p class="tier__desc"><?php esc_html_e( 'For chains, franchises and high volume.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"tier__list"} -->
<ul class="wp-block-list tier__list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Volume pricing on fees', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Custom roles and approvals', 'byahe' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'A dedicated contact', 'byahe' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secondary opens-demo-dialog"} -->
<div class="wp-block-button is-style-secondary opens-demo-dialog"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( page_url( 'about' ) . '#demo-note' ); ?>"><?php esc_html_e( 'Talk to us', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:byahe/pricing-card -->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"mt-8 mb-0 text-sm"} -->
<p class="mt-8 mb-0 text-sm"><?php esc_html_e( 'Prices include VAT. Yearly plans are billed once a year.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:byahe/demo-dialog /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
