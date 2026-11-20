<?php
/**
 * Title: About: Credits
 * Slug: byahe/about-credits
 * Categories: byahe, about
 * Description: Photo, font and icon credits. Photo credits come from the theme's photos.json.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

use function Byahe\Theme\photos;

$byahe_fonts = array(
	array( 'Cubao Free', __( 'Aaron Amar', 'byahe' ), 'https://aaronamar.gumroad.com/l/cubaofreev2', 'aaronamar.gumroad.com', __( 'free for personal and commercial use', 'byahe' ) ),
	array( 'Inter', __( 'Rasmus Andersson', 'byahe' ), 'https://fontsource.org/fonts/inter', 'Fontsource', __( 'SIL Open Font License 1.1', 'byahe' ) ),
	array( 'Space Grotesk', __( 'Florian Karsten', 'byahe' ), 'https://fontsource.org/fonts/space-grotesk', 'Fontsource', __( 'SIL Open Font License 1.1', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"credits","className":"bg-cream scroll-mt-4","layout":{"type":"default"}} -->
<section id="credits" class="wp-block-group bg-cream scroll-mt-4">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"credits-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="credits-title"><?php esc_html_e( 'Credits.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[40rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[40rem] text-lg"><?php esc_html_e( 'The photos, fonts and icons on this site, and where they come from.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"credits-grid","layout":{"type":"default"}} -->
<div class="wp-block-group credits-grid">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Photos', 'byahe' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"credits-list"} -->
<ul class="wp-block-list credits-list">
<?php
foreach ( photos() as $byahe_photo ) :
	$byahe_source = ucfirst( (string) ( $byahe_photo['source'] ?? 'pexels' ) );
	$byahe_by     = $byahe_photo['photographer'] ?? '';
	if ( ! $byahe_by ) {
		/* translators: %s: photo site, e.g. Pexels. */
		$byahe_by = sprintf( __( '%s contributor', 'byahe' ), $byahe_source );
	}
	?>
<!-- wp:list-item -->
<li><span class="ui-strong"><?php echo esc_html( $byahe_photo['alt'] ); ?></span><br>
	<?php
	printf(
		/* translators: 1: photographer, 2: link to the photo page, 3: license name. */
		esc_html__( '%1$s on %2$s, %3$s', 'byahe' ),
		esc_html( $byahe_by ),
		'<a href="' . esc_url( $byahe_photo['pageUrl'] ) . '">' . esc_html( $byahe_source . ' #' . $byahe_photo['id'] ) . '</a>',
		/* translators: %s: photo site, e.g. Pexels. */
		esc_html( sprintf( __( '%s License', 'byahe' ), $byahe_source ) )
	);
	?>
</li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Fonts', 'byahe' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"credits-list"} -->
<ul class="wp-block-list credits-list">
<?php foreach ( $byahe_fonts as $byahe_font ) : ?>
<!-- wp:list-item -->
<li><span class="ui-strong"><?php echo esc_html( $byahe_font[0] ); ?></span><br><?php echo esc_html( $byahe_font[1] ); ?>, <a href="<?php echo esc_url( $byahe_font[2] ); ?>"><?php echo esc_html( $byahe_font[3] ); ?></a>, <?php echo esc_html( $byahe_font[4] ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Icons', 'byahe' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"credits-list"} -->
<ul class="wp-block-list credits-list">
<!-- wp:list-item -->
<li><span class="ui-strong">Lucide</span><br><a href="https://lucide.dev">lucide.dev</a>, <?php esc_html_e( 'ISC License', 'byahe' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
