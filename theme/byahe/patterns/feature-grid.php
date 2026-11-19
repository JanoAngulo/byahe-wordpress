<?php
/**
 * Title: Feature grid
 * Slug: byahe/feature-grid
 * Categories: byahe, featured
 * Description: Six signboard cards, each linking to its section on the Product page.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;

$byahe_features = array(
	array( 'invoicing', 'file-text', __( 'Invoicing', 'byahe' ), __( 'Branded invoices with your logo, TIN and payment options built in.', 'byahe' ) ),
	array( 'payment-links', 'qr-code', __( 'Payment links and QR', 'byahe' ), __( 'A link or a printed QR Ph code for the counter. Customers pay in seconds.', 'byahe' ) ),
	array( 'reminders', 'bell-ring', __( 'Auto reminders', 'byahe' ), __( 'Polite nudges before and after the due date, so you never have to chase.', 'byahe' ) ),
	array( 'cash-flow', 'line-chart', __( 'Cash-flow dashboard', 'byahe' ), __( "What came in, what's due this week, and who's late, on one screen.", 'byahe' ) ),
	array( 'bir-receipts', 'receipt', __( 'BIR-ready receipts', 'byahe' ), __( 'Every payment issues a receipt with the details your bookkeeper asks for.', 'byahe' ) ),
	array( 'team-access', 'users', __( 'Team access', 'byahe' ), __( 'Give your cashier, bookkeeper or partner their own login and the right access.', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"features","className":"bg-yellow border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="features" class="wp-block-group bg-yellow border-b-2 border-navy">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"features-title","className":"m-0 text-4xl max-w-[20ch]"} -->
<h2 class="wp-block-heading m-0 text-4xl max-w-[20ch]" id="features-title"><?php esc_html_e( 'Everything a small business needs to get paid.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"tagName":"ul","className":"mt-10 mb-0 p-0 list-none grid gap-6 sm:grid-cols-2 lg:grid-cols-3","layout":{"type":"default"}} -->
<ul class="wp-block-group mt-10 mb-0 p-0 list-none grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
<?php foreach ( $byahe_features as $byahe_feature ) : ?>
<!-- wp:group {"tagName":"li","className":"card-hard feature-card has-stretched-link","layout":{"type":"default"}} -->
<li class="wp-block-group card-hard feature-card has-stretched-link">
<!-- wp:byahe/icon {"icon":"<?php echo esc_attr( $byahe_feature[1] ); ?>","className":"card-sign"} /-->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="<?php echo esc_url( page_url( 'product' ) . '#' . $byahe_feature[0] ); ?>"><?php echo esc_html( $byahe_feature[2] ); ?></a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $byahe_feature[3] ); ?></p>
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
