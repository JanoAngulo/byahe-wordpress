<?php
/**
 * Title: About: Demo note
 * Slug: byahe/about-demo-note
 * Categories: byahe, about
 * Description: Says BYAHE is a portfolio project, how it was built, and where to see the code.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_built = array(
	__( 'Designed in HTML and Tailwind CSS first', 'byahe' ),
	__( 'One set of design tokens for Tailwind and WordPress', 'byahe' ),
	__( 'Custom WordPress block theme and blocks', 'byahe' ),
	__( 'Accessibility, spacing and type tested on every page', 'byahe' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"demo-note","className":"bg-cream border-b-2 border-navy scroll-mt-4","layout":{"type":"default"}} -->
<section id="demo-note" class="wp-block-group bg-cream border-b-2 border-navy scroll-mt-4">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:group {"className":"card-hard card-hard-lg bg-yellow! p-8 md:p-12 grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:gap-16","layout":{"type":"default"}} -->
<div class="wp-block-group card-hard card-hard-lg bg-yellow! p-8 md:p-12 grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:gap-16">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"anchor":"demo-note-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="demo-note-title"><?php esc_html_e( 'This is a demo company.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-6 mb-0 text-lg"} -->
<p class="mt-6 mb-0 text-lg"><?php esc_html_e( 'BYAHE is not a real company or financial service. It’s a portfolio project by John Russel Angulo, a frontend developer and UI/UX designer in the Philippines. Every customer, team member, figure and quote on this site is made up.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"level":3,"className":"mt-0 mb-4"} -->
<h3 class="wp-block-heading mt-0 mb-4"><?php esc_html_e( 'How it was built', 'byahe' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"benefits built-list mt-0"} -->
<ul class="wp-block-list benefits built-list mt-0">
<?php foreach ( $byahe_built as $byahe_item ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $byahe_item ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"mt-8","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-buttons mt-8"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://github.com/JanoAngulo/byahe-wordpress"><?php esc_html_e( 'View the code on GitHub', 'byahe' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-secondary"} -->
<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button" href="https://jrla1219.web.app"><?php esc_html_e( 'See John’s portfolio', 'byahe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
