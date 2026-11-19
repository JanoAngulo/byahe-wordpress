<?php
/**
 * Title: How it works
 * Slug: byahe/how-it-works
 * Categories: byahe, text
 * Description: Three numbered steps on hard-shadow cards.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_steps = array(
	array(
		__( 'Send the bill', 'byahe' ),
		__( 'Make an invoice in under a minute, or turn any amount into a payment link. Send it by SMS, Messenger or email.', 'byahe' ),
	),
	array(
		__( 'They pay their way', 'byahe' ),
		__( 'Your customer scans the QR Ph code, pays by card, or sends a bank transfer. No app download on their side.', 'byahe' ),
	),
	array(
		__( 'Money lands, books update', 'byahe' ),
		__( 'The invoice flips to Paid, a BIR-ready receipt goes out, and your cash-flow view updates itself.', 'byahe' ),
	),
);
?>
<!-- wp:group {"tagName":"section","anchor":"how-it-works","className":"bg-cream","layout":{"type":"default"}} -->
<section id="how-it-works" class="wp-block-group bg-cream">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"how-title","className":"m-0 text-4xl max-w-[18ch]"} -->
<h2 class="wp-block-heading m-0 text-4xl max-w-[18ch]" id="how-title"><?php esc_html_e( 'From invoice to “salamat po” in three stops.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"tagName":"ol","className":"steps cards-3 mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3","layout":{"type":"default"}} -->
<ol class="wp-block-group steps cards-3 mt-10 mb-0 p-0 list-none grid gap-6 lg:grid-cols-3">
<?php foreach ( $byahe_steps as $byahe_step ) : ?>
<!-- wp:group {"tagName":"li","className":"card-hard p-6","layout":{"type":"default"}} -->
<li class="wp-block-group card-hard p-6">
<!-- wp:heading {"level":3,"className":"mt-0 mb-2 text-xl"} -->
<h3 class="wp-block-heading mt-0 mb-2 text-xl"><?php echo esc_html( $byahe_step[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"m-0"} -->
<p class="m-0"><?php echo esc_html( $byahe_step[1] ); ?></p>
<!-- /wp:paragraph -->
</li>
<!-- /wp:group -->
<?php endforeach; ?>
</ol>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
