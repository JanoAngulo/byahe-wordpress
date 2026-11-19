<?php
/**
 * Render for byahe/ui-mockup: resolves the variant's fields, then includes
 * templates/<variant>.php inside the shared mockup wrapper.
 *
 * @package ByaheCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$byahe_variants = Byahe\Core\schema( 'ui-mockup' );
$byahe_variant  = (string) ( $attributes['variant'] ?? '' );
$byahe_template = __DIR__ . '/templates/' . $byahe_variant . '.php';

if ( ! isset( $byahe_variants[ $byahe_variant ] ) || ! file_exists( $byahe_template ) ) {
	return;
}

$byahe_data = Byahe\Core\resolve_fields( $byahe_variants[ $byahe_variant ], (array) ( $attributes['values'] ?? array() ) );
$f          = $byahe_data['fields']; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template variable.
$l          = $byahe_data['lists']; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template variable.
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'ui-mockup ui-mockup--' . $byahe_variant ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="img" aria-label="<?php echo esc_attr( $byahe_data['aria'] ); ?>">
<?php require $byahe_template; ?>
</div>
