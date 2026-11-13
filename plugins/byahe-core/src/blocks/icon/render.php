<?php
/**
 * Render for byahe/icon.
 *
 * @package ByaheCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$byahe_is_sign = str_contains( (string) ( $attributes['className'] ?? '' ), 'card-sign' );
$byahe_svg     = Byahe\Core\icon_svg( (string) ( $attributes['icon'] ?? '' ), $byahe_is_sign ? 'card-sign__icon' : 'byahe-icon' );

if ( '' === $byahe_svg ) {
	return;
}

printf(
	'<div %1$s>%2$s</div>',
	get_block_wrapper_attributes(), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core escapes the attributes.
	$byahe_svg // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from a local allow-listed SVG file.
);
