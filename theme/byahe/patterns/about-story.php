<?php
/**
 * Title: About: Story
 * Slug: byahe/about-story
 * Categories: byahe, about
 * Description: How the company started, beside a duotone street photo.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\photo;

$byahe_paragraphs = array(
	__( 'In 2023, our co-founder Rina was helping at her family’s bakery in Malolos. Half the regulars paid in cash, a third paid “next week”, and the rest sent screenshots nobody could match to an order.', 'byahe' ),
	__( 'She and Paolo, an engineer from the same town, built a simple QR invoice over one long weekend. Ten neighboring shops asked for it within a month. That was the first BYAHE.', 'byahe' ),
	__( 'Today the team works out of an office in BGC, but every feature still gets tested at a real counter in Bulacan first.', 'byahe' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"story","className":"bg-cream border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="story" class="wp-block-group bg-cream border-b-2 border-navy">
<!-- wp:group {"className":"container grid gap-12 py-16 md:py-24 lg:grid-cols-2 lg:items-center","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-12 py-16 md:py-24 lg:grid-cols-2 lg:items-center">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"anchor":"story-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="story-title"><?php esc_html_e( 'It started with a notebook of utang.', 'byahe' ); ?></h2>
<!-- /wp:heading -->
<?php foreach ( $byahe_paragraphs as $byahe_i => $byahe_text ) : ?>
	<?php $byahe_class = ( 0 === $byahe_i ? 'mt-6' : 'mt-4' ) . ' mb-0 max-w-[36rem] text-lg'; ?>

<!-- wp:paragraph {"className":"<?php echo esc_attr( $byahe_class ); ?>"} -->
<p class="<?php echo esc_attr( $byahe_class ); ?>"><?php echo esc_html( $byahe_text ); ?></p>
<!-- /wp:paragraph -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

<?php
echo photo( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built with escaped values.
	'jeepney-street',
	array(
		'class'   => 'photo-duotone m-0 aspect-[4/5] lg:ml-8',
		'duotone' => true,
	)
);
?>
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
