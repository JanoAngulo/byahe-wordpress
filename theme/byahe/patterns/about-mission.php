<?php
/**
 * Title: About: Mission
 * Slug: byahe/about-mission
 * Categories: byahe, about
 * Description: Mission statement beside a duotone skyline photo.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\photo;

?>
<!-- wp:group {"tagName":"section","anchor":"mission","className":"bg-yellow border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="mission" class="wp-block-group bg-yellow border-b-2 border-navy">
<!-- wp:group {"className":"container grid gap-12 py-16 md:py-24 lg:grid-cols-[1.2fr_1fr] lg:items-center","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-12 py-16 md:py-24 lg:grid-cols-[1.2fr_1fr] lg:items-center">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"anchor":"mission-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="mission-title"><?php esc_html_e( 'Our mission.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-6 mb-0 font-heading font-bold text-3xl"} -->
<p class="mt-6 mb-0 font-heading font-bold text-3xl"><?php esc_html_e( 'Every small business in the Philippines should get paid on time, in full, without chasing anyone.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 mb-0 max-w-[36rem] text-lg"} -->
<p class="mt-6 mb-0 max-w-[36rem] text-lg"><?php esc_html_e( 'From a bakery in Malolos to an office in BGC, that’s the only test a new feature has to pass.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<?php
echo photo( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built with escaped values.
	'city-skyline',
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
