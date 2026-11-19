<?php
/**
 * Title: Payment methods
 * Slug: byahe/payment-methods
 * Categories: byahe, text
 * Description: Payment methods as text chips next to a photo. No third-party logos.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\photo;

$byahe_methods = array(
	__( 'QR Ph', 'byahe' ),
	__( 'Credit and debit cards', 'byahe' ),
	__( 'InstaPay', 'byahe' ),
	__( 'PESONet', 'byahe' ),
	__( 'E-wallets', 'byahe' ),
	__( 'Over-the-counter', 'byahe' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"payment-methods","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="payment-methods" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container grid gap-10 py-16 md:py-24 lg:grid-cols-2 lg:items-center","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-10 py-16 md:py-24 lg:grid-cols-2 lg:items-center">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"anchor":"methods-title","className":"m-0 text-4xl max-w-[16ch]"} -->
<h2 class="wp-block-heading m-0 text-4xl max-w-[16ch]" id="methods-title"><?php esc_html_e( 'Let them pay however they like.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[36rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[36rem] text-lg"><?php esc_html_e( 'Every invoice and payment link accepts all of these at once. Your customer picks, and you get one clean record.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"method-chips mt-8 mb-0 p-0 list-none flex flex-wrap gap-3"} -->
<ul class="wp-block-list method-chips mt-8 mb-0 p-0 list-none flex flex-wrap gap-3">
<?php foreach ( $byahe_methods as $byahe_method ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $byahe_method ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->

<?php
echo photo( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built with escaped values.
	'qr-payment-hands',
	array(
		'class'   => 'photo-duotone m-0 aspect-[3/2]',
		'duotone' => true,
	)
);
?>
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
