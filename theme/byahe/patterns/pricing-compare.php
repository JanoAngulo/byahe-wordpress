<?php
/**
 * Title: Plan comparison
 * Slug: byahe/pricing-compare
 * Categories: byahe, text
 * Description: Feature-by-plan table. Type Yes or — in a cell to show an included or not-included mark.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

?>
<!-- wp:group {"tagName":"section","anchor":"compare","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="compare" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"compare-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="compare-title"><?php esc_html_e( 'Compare every plan.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:table {"className":"table-scroll data-table--compare mt-10"} -->
<figure class="wp-block-table table-scroll data-table--compare mt-10"><table><thead><tr><th scope="col"><?php esc_html_e( 'Feature', 'byahe' ); ?></th><th scope="col"><?php esc_html_e( 'Libre', 'byahe' ); ?></th><th scope="col"><?php esc_html_e( 'Negosyo', 'byahe' ); ?></th><th scope="col"><?php esc_html_e( 'Pro', 'byahe' ); ?></th><th scope="col"><?php esc_html_e( 'Enterprise', 'byahe' ); ?></th></tr></thead><tbody><tr><th scope="row"><?php echo esc_html__( 'Invoices per month', 'byahe' ); ?></th><td>10</td><td><?php echo esc_html__( 'Unlimited', 'byahe' ); ?></td><td><?php echo esc_html__( 'Unlimited', 'byahe' ); ?></td><td><?php echo esc_html__( 'Unlimited', 'byahe' ); ?></td></tr><tr><th scope="row"><?php echo esc_html__( 'Payment links and QR Ph', 'byahe' ); ?></th><td>Yes</td><td>Yes</td><td>Yes</td><td>Yes</td></tr><tr><th scope="row"><?php echo esc_html__( 'BIR-ready receipts', 'byahe' ); ?></th><td>Yes</td><td>Yes</td><td>Yes</td><td>Yes</td></tr><tr><th scope="row"><?php echo esc_html__( 'Automatic reminders', 'byahe' ); ?></th><td>—</td><td>Yes</td><td>Yes</td><td>Yes</td></tr><tr><th scope="row"><?php echo esc_html__( 'Cash-flow dashboard', 'byahe' ); ?></th><td>—</td><td>—</td><td>Yes</td><td>Yes</td></tr><tr><th scope="row"><?php echo esc_html__( 'Team members', 'byahe' ); ?></th><td>1</td><td>2</td><td>10</td><td><?php echo esc_html__( 'Unlimited', 'byahe' ); ?></td></tr><tr><th scope="row"><?php echo esc_html__( 'Webhooks and API', 'byahe' ); ?></th><td>—</td><td>—</td><td>Yes</td><td>Yes</td></tr><tr><th scope="row"><?php echo esc_html__( 'Support', 'byahe' ); ?></th><td><?php echo esc_html__( 'Email', 'byahe' ); ?></td><td><?php echo esc_html__( 'Email and chat', 'byahe' ); ?></td><td><?php echo esc_html__( 'Priority chat', 'byahe' ); ?></td><td><?php echo esc_html__( 'Dedicated contact', 'byahe' ); ?></td></tr></tbody></table><figcaption class="wp-element-caption"><?php esc_html_e( 'What\'s in each plan', 'byahe' ); ?></figcaption></figure>
<!-- /wp:table -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
