<?php
/**
 * Title: About: Team
 * Slug: byahe/about-team
 * Categories: byahe, team
 * Description: Six team members with initials avatars, roles and a line each.
 * Viewport Width: 1280
 *
 * @package Byahe
 */

$byahe_team = array(
	array( 'RV', 'Rina Villanueva', __( 'Co-founder, Product', 'byahe' ), __( 'Still does Saturday shifts at the family bakery. Calls it user research.', 'byahe' ) ),
	array( 'PC', 'Paolo Cruz', __( 'Co-founder, Engineering', 'byahe' ), __( 'Built the first QR invoice in a weekend. Has been rewriting it ever since.', 'byahe' ) ),
	array( 'GN', 'Gab Navarro', __( 'Design', 'byahe' ), __( 'Makes sure every screen reads clearly on a cracked phone in direct sunlight.', 'byahe' ) ),
	array( 'TR', 'Tess Ramos', __( 'Customer success', 'byahe' ), __( 'Knows half our customers by first name and the other half by their suki.', 'byahe' ) ),
	array( 'MT', 'Migs Tolentino', __( 'Payments and risk', 'byahe' ), __( 'The reason your payouts land on time, and the reason fraud doesn’t.', 'byahe' ) ),
	array( 'JB', 'Joy Bautista', __( 'Partnerships', 'byahe' ), __( 'Works with banks and e-wallets so your customers can pay however they like.', 'byahe' ) ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"team","className":"bg-yellow border-b-2 border-navy","layout":{"type":"default"}} -->
<section id="team" class="wp-block-group bg-yellow border-b-2 border-navy">
<!-- wp:group {"className":"container py-16 md:py-24","layout":{"type":"default"}} -->
<div class="wp-block-group container py-16 md:py-24">
<!-- wp:heading {"anchor":"team-title","className":"m-0 text-4xl"} -->
<h2 class="wp-block-heading m-0 text-4xl" id="team-title"><?php esc_html_e( 'The crew.', 'byahe' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mt-4 mb-0 max-w-[40rem] text-lg"} -->
<p class="mt-4 mb-0 max-w-[40rem] text-lg"><?php esc_html_e( 'Twelve people, two offices, one long group chat. Here are some of them.', 'byahe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"tagName":"ul","className":"mt-10 mb-0 p-0 list-none grid gap-6 sm:grid-cols-2 lg:grid-cols-3","layout":{"type":"default"}} -->
<ul class="wp-block-group mt-10 mb-0 p-0 list-none grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
<?php foreach ( $byahe_team as $byahe_member ) : ?>
<!-- wp:group {"tagName":"li","className":"card-hard member","layout":{"type":"default"}} -->
<li class="wp-block-group card-hard member">
<!-- wp:paragraph {"className":"avatar"} -->
<p class="avatar"><?php echo esc_html( $byahe_member[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $byahe_member[1] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"member__role"} -->
<p class="member__role"><?php echo esc_html( $byahe_member[2] ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $byahe_member[3] ); ?></p>
<!-- /wp:paragraph -->
</li>
<!-- /wp:group -->
<?php endforeach; ?>
</ul>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
