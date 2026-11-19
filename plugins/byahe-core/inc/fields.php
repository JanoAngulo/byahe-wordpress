<?php
/**
 * Schema-driven fields for blocks whose content is a set of editable strings
 * and rows (hero dashboard, UI mockups). One JSON schema per block holds the
 * labels and defaults for both the editor panel and the render.
 *
 * @package ByaheCore
 */

namespace Byahe\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Loads a schema from inc/schemas/<name>.json.
 *
 * @param string $name Schema name.
 * @return array Decoded schema, or an empty array when missing.
 */
function schema( string $name ): array {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$path           = BYAHE_CORE_DIR . '/inc/schemas/' . sanitize_file_name( $name ) . '.json';
		$cache[ $name ] = file_exists( $path ) ? (array) wp_json_file_decode( $path, array( 'associative' => true ) ) : array();
	}
	return $cache[ $name ];
}

/**
 * Merges saved values over a schema's defaults.
 *
 * Fields override key by key; a saved list replaces the whole default list.
 * Keys the schema doesn't define are dropped, and every string is sanitized.
 *
 * @param array $schema Schema with `fields`, `lists` and optional `ariaLabel`.
 * @param array $values Saved block values.
 * @return array{fields: array<string, string>, lists: array<string, list<array<string, string>>>, aria: string}
 */
function resolve_fields( array $schema, array $values ): array {
	$fields = array();
	foreach ( (array) ( $schema['fields'] ?? array() ) as $key => $def ) {
		$value          = $values[ $key ] ?? $def['default'] ?? '';
		$fields[ $key ] = sanitize_text_field( is_scalar( $value ) ? (string) $value : '' );
	}

	$lists = array();
	foreach ( (array) ( $schema['lists'] ?? array() ) as $key => $def ) {
		$rows    = isset( $values[ $key ] ) && is_array( $values[ $key ] ) ? $values[ $key ] : (array) ( $def['default'] ?? array() );
		$columns = array_keys( (array) ( $def['columns'] ?? array() ) );
		$clean   = array();
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$item = array();
			foreach ( $columns as $column ) {
				$cell            = $row[ $column ] ?? '';
				$item[ $column ] = sanitize_text_field( is_scalar( $cell ) ? (string) $cell : '' );
			}
			$clean[] = $item;
		}
		$lists[ $key ] = $clean;
	}

	return array(
		'fields' => $fields,
		'lists'  => $lists,
		'aria'   => aria_label( $schema, $fields, $lists ),
	);
}

/**
 * Fills the schema's `ariaLabel` template. `{field}` takes a field value;
 * `{list}` takes the list's rows, each formatted with the list's `ariaRow`
 * template and joined with commas.
 *
 * @param array $schema Schema.
 * @param array $fields Resolved fields.
 * @param array $lists  Resolved lists.
 */
function aria_label( array $schema, array $fields, array $lists ): string {
	$template = (string) ( $schema['ariaLabel'] ?? '' );
	if ( '' === $template ) {
		return '';
	}

	$replacements = array();
	foreach ( $fields as $key => $value ) {
		$replacements[ '{' . $key . '}' ] = $value;
	}
	foreach ( $lists as $key => $rows ) {
		$row_template = (string) ( $schema['lists'][ $key ]['ariaRow'] ?? '' );
		$parts        = array();
		foreach ( $rows as $row ) {
			$row_map = array();
			foreach ( $row as $column => $cell ) {
				$row_map[ '{' . $column . '}' ] = $cell;
			}
			$parts[] = strtr( $row_template, $row_map );
		}
		$replacements[ '{' . $key . '}' ]       = implode( ', ', $parts );
		$replacements[ '{' . $key . '.count}' ] = (string) count( $rows );
	}

	return trim( (string) preg_replace( '/\s+/', ' ', strtr( $template, $replacements ) ) );
}

/**
 * Pill colour class for a status word in a mockup.
 *
 * @param string $status Status text, e.g. Paid, Pending, Overdue.
 */
function pill_class( string $status ): string {
	$map = array(
		'paid'      => 'pill-paid',
		'sent'      => 'pill-paid',
		'open'      => 'pill-paid',
		'active'    => 'pill-paid',
		'overdue'   => 'pill-overdue',
		'expired'   => 'pill-overdue',
		'failed'    => 'pill-overdue',
		'pending'   => 'pill-pending',
		'draft'     => 'pill-pending',
		'link sent' => 'pill-pending',
	);
	return $map[ strtolower( trim( $status ) ) ] ?? 'pill-pending';
}
