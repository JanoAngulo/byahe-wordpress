<?php
/**
 * Title: Home hero
 * Slug: byahe/home-hero
 * Categories: byahe, banner
 * Description: Yellow hero with the headline, two buttons, a market photo and the live dashboard.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\page_url;
use function Byahe\Theme\photo;

?>
<!-- wp:group {"tagName":"section","anchor":"hero","className":"bg-yellow border-b-2 border-navy overflow-hidden","layout":{"type":"default"}} -->
<section id="hero" class="wp-block-group bg-yellow border-b-2 border-navy overflow-hidden">
<!-- wp:group {"className":"container grid gap-12 pt-12 pb-16 md:pt-16 md:pb-24 lg:grid-cols-[1.05fr_1fr] lg:items-center","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-12 pt-12 pb-16 md:pt-16 md:pb-24 lg:grid-cols-[1.05fr_1fr] lg:items-center">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"level":1,"anchor":"hero-title","className":"m-0 text-6xl text-pretty max-w-[13ch]"} -->
<h1 class="wp-block-heading m-0 text-6xl text-pretty max-w-[13ch]" id="hero-title"><?php esc_html_e( 'Bayad na, tuloy ang byahe.', 'byahe' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-6 mb-0 max-w-[34rem] text-lg"} -->
<p class="mt-6 mb-0 max-w-[34rem] text-lg"><?php esc_html_e( 'Invoices and payments for small businesses across the Philippines. Send a bill, get paid by QR Ph, card or bank transfer, and watch the money land.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-8 gap-4"} -->
<div class="wp-block-buttons mt-8 gap-4"><!-- wp:button {"fontSize":"lg"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-lg-font-size has-custom-font-size wp-element-button" href="<?php echo esc_url( page_url( 'pricing' ) . '#libre' ); ?>"><?php esc_html_e( 'Sakay na →', 'byahe' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-secondary","fontSize":"lg"} -->
<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link has-lg-font-size has-custom-font-size wp-element-button" href="<?php echo esc_url( page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'See pricing', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"mt-5 mb-0 text-sm font-semibold"} -->
<p class="mt-5 mb-0 text-sm font-semibold"><?php esc_html_e( 'Libre for your first 10 invoices a month. No card needed.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"relative pb-28 sm:pb-20 lg:pb-0","layout":{"type":"default"}} -->
<div class="wp-block-group relative pb-28 sm:pb-20 lg:pb-0">
<?php
echo photo( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built with escaped values.
	'hero-market',
	array(
		'class'    => 'photo-duotone m-0 aspect-[4/3] lg:aspect-[4/5] lg:ml-16',
		'duotone'  => true,
		'priority' => true,
	)
);
?>

<!-- wp:group {"className":"absolute left-0 right-4 -bottom-2 sm:right-auto sm:w-[25rem] lg:-left-8 lg:bottom-10","layout":{"type":"default"}} -->
<div class="wp-block-group absolute left-0 right-4 -bottom-2 sm:right-auto sm:w-[25rem] lg:-left-8 lg:bottom-10">
<!-- wp:byahe/hero-dashboard /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
