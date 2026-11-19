<?php
/**
 * Shared section markup for patterns that repeat one layout with different
 * content (Product features, Solutions industries).
 *
 * @package Byahe
 */

namespace Byahe\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Block attributes as JSON for a block comment.
 *
 * @param array $attrs Attributes.
 */
function block_attrs( array $attrs ): string {
	return (string) wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
}

/**
 * Two-column section: heading, lead, three bullets (and optional extras) on
 * one side, a media block on the other.
 *
 * @param array $args {
 *     Section content.
 *
 *     @type string $id      Section id (anchor).
 *     @type string $bg      Background: yellow or cream.
 *     @type string $title   Heading (h2).
 *     @type string $lead    Lead paragraph.
 *     @type array  $bullets Benefit bullets.
 *     @type string $media   Block markup for the other column.
 *     @type bool   $reverse Put the media first on desktop.
 *     @type bool   $last    Drop the bottom border.
 *     @type string $note    Optional small print under the bullets.
 *     @type array  $quote   Optional customer quote: text, name, business, initials.
 * }
 * @return string Block markup.
 */
function feature_section( array $args ): string {
	$args = wp_parse_args(
		$args,
		array(
			'bg'      => 'cream',
			'bullets' => array(),
			'reverse' => false,
			'last'    => false,
			'note'    => '',
			'quote'   => array(),
		)
	);

	$id           = sanitize_html_class( $args['id'] );
	$section_cls  = 'bg-' . sanitize_html_class( $args['bg'] ) . ( $args['last'] ? '' : ' border-b-2 border-navy' ) . ' scroll-mt-4';
	$text_cls     = $args['reverse'] ? 'lg:order-2' : '';
	$media_cls    = $args['reverse'] ? 'lg:order-1' : '';
	$text_attrs   = array( 'layout' => array( 'type' => 'default' ) ) + ( $text_cls ? array( 'className' => $text_cls ) : array() );
	$media_attrs  = array( 'layout' => array( 'type' => 'default' ) ) + ( $media_cls ? array( 'className' => $media_cls ) : array() );
	$text_class   = trim( 'wp-block-group ' . $text_cls );
	$media_class  = trim( 'wp-block-group ' . $media_cls );
	$bullet_items = '';
	foreach ( $args['bullets'] as $bullet ) {
		$bullet_items .= "<!-- wp:list-item -->\n<li>" . esc_html( $bullet ) . "</li>\n<!-- /wp:list-item -->\n";
	}

	$section_attrs = array(
		'tagName'   => 'section',
		'anchor'    => $id,
		'className' => $section_cls,
		'layout'    => array( 'type' => 'default' ),
	);
	$title_attrs   = array(
		'anchor'    => $id . '-title',
		'className' => 'm-0 text-4xl',
	);

	ob_start();
	?>
<!-- wp:group <?php echo block_attrs( $section_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<section id="<?php echo esc_attr( $id ); ?>" class="wp-block-group <?php echo esc_attr( $section_cls ); ?>">
<!-- wp:group {"className":"container grid gap-12 py-16 md:py-24 lg:grid-cols-2 lg:items-center","layout":{"type":"default"}} -->
<div class="wp-block-group container grid gap-12 py-16 md:py-24 lg:grid-cols-2 lg:items-center">
<!-- wp:group <?php echo block_attrs( $text_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<div class="<?php echo esc_attr( $text_class ); ?>">
<!-- wp:heading <?php echo block_attrs( $title_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<h2 class="wp-block-heading m-0 text-4xl" id="<?php echo esc_attr( $id . '-title' ); ?>"><?php echo esc_html( $args['title'] ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[36rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[36rem] text-lg"><?php echo esc_html( $args['lead'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"benefits"} -->
<ul class="wp-block-list benefits">
	<?php echo $bullet_items; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
</ul>
<!-- /wp:list -->
	<?php if ( $args['note'] ) : ?>

<!-- wp:paragraph {"className":"mt-6 mb-0 text-sm"} -->
<p class="mt-6 mb-0 text-sm"><?php echo esc_html( $args['note'] ); ?></p>
<!-- /wp:paragraph -->
	<?php endif; ?>
	<?php if ( $args['quote'] ) : ?>

<!-- wp:group {"tagName":"figure","className":"mini-quote","layout":{"type":"default"}} -->
<figure class="wp-block-group mini-quote">
<!-- wp:quote {"className":"m-0"} -->
<blockquote class="wp-block-quote m-0"><!-- wp:paragraph {"className":"m-0"} -->
<p class="m-0"><?php echo esc_html( $args['quote']['text'] ); ?></p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:group {"tagName":"figcaption","className":"mini-quote__by","layout":{"type":"default"}} -->
<figcaption class="wp-block-group mini-quote__by">
<!-- wp:paragraph {"className":"avatar m-0"} -->
<p class="avatar m-0"><?php echo esc_html( $args['quote']['initials'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"m-0"} -->
<p class="m-0"><strong><?php echo esc_html( $args['quote']['name'] ); ?></strong><br><?php echo esc_html( $args['quote']['business'] ); ?></p>
<!-- /wp:paragraph -->
</figcaption>
<!-- /wp:group -->
</figure>
<!-- /wp:group -->
	<?php endif; ?>
</div>
<!-- /wp:group -->

<!-- wp:group <?php echo block_attrs( $media_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<div class="<?php echo esc_attr( $media_class ); ?>">
	<?php echo $args['media']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup from the pattern. ?>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
	<?php
	return (string) ob_get_clean();
}
