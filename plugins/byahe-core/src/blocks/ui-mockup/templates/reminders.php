<?php
/**
 * UI mockup variant: reminders. Markup mirrors prototype/partials/mockups/reminders.html.
 *
 * @package ByaheCore
 *
 * @var array $f Resolved fields.
 * @var array $l Resolved lists.
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template scope, included from render.php.
?>
<div class="ui-head">
	<p class="ui-title"><?php esc_html_e( 'Reminders,', 'byahe-core' ); ?> <span class="num"><?php echo esc_html( $f['number'] ); ?></span></p>
	<span class="pill <?php echo esc_attr( Byahe\Core\pill_class( $f['status'] ) ); ?>"><?php echo esc_html( $f['status'] ); ?></span>
</div>
<ol class="ui-timeline">
<?php foreach ( $l['steps'] as $r ) : ?>
	<li class="<?php echo esc_attr( 'paid' === $r['state'] ? 'is-paid' : 'is-done' ); ?>"><p class="ui-strong"><?php echo esc_html( $r['title'] ); ?></p><p class="ui-muted"><?php echo esc_html( $r['when'] ); ?></p></li>
<?php endforeach; ?>
</ol>
