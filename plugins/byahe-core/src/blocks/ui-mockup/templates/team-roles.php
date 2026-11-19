<?php
/**
 * UI mockup variant: team-roles. Markup mirrors prototype/partials/mockups/team-roles.html.
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
	<p class="ui-title"><?php echo esc_html( $f['title'] ); ?></p>
	<span class="ui-btn ui-btn--primary"><?php echo esc_html( $f['button'] ); ?></span>
</div>
<ul class="ui-team">
<?php foreach ( $l['members'] as $r ) : ?>
	<li><span class="ui-avatar"><?php echo esc_html( $r['initials'] ); ?></span><span><span class="ui-strong"><?php echo esc_html( $r['name'] ); ?></span><br><span class="ui-muted"><?php echo esc_html( $r['access'] ); ?></span></span><span class="ui-role <?php echo esc_attr( 'ui-role--' . sanitize_html_class( strtolower( $r['role'] ) ) ); ?>"><?php echo esc_html( $r['role'] ); ?></span></li>
<?php endforeach; ?>
</ul>
