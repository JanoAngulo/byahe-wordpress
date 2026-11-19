<?php
/**
 * Title: Integrations
 * Slug: byahe/product-integrations
 * Categories: byahe, featured
 * Description: Three integration cards.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_cards = array(
	array( 'calculator', __( 'Accounting exports', 'byahe' ), __( 'Monthly sales, VAT and receipts in the format your accounting software imports.', 'byahe' ) ),
	array( 'sheet', __( 'Spreadsheets', 'byahe' ), __( 'Download any list as CSV, or keep a spreadsheet updated automatically.', 'byahe' ) ),
	array( 'webhook', __( 'Webhooks and API', 'byahe' ), __( 'On Pro, connect your own systems. Get notified on every payment in real time.', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"integrations","className":"bg-cream","layout":{"type":"default"}} -->
<section id="integrations" class="wp-block-group bg-cream">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"integrations-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="integrations-title"><?php esc_html_e( 'Plays well with your other tools.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[40rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[40rem] text-lg"><?php esc_html_e( 'Keep your books where they are. BYAHE sends the numbers there.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"tagName":"ul","className":"cards-3 mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3","layout":{"type":"default"}} -->
<ul class="wp-block-group cards-3 mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3">
<?php foreach ( $byahe_cards as $byahe_card ) : ?>
<!-- wp:group {"tagName":"li","className":"card-hard feature-card","layout":{"type":"default"}} -->
<li class="wp-block-group card-hard feature-card">
<!-- wp:byahe/icon {"icon":"<?php echo esc_attr( $byahe_card[0] ); ?>","className":"card-sign"} /-->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $byahe_card[1] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $byahe_card[2] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</li>
<!-- /wp:group -->
<?php endforeach; ?>
</ul>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
