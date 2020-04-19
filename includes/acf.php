<?php
/**
 * ACF
 *
 * @package DLAP
 */

add_filter( 'acf/load_field/key=field_5e99e4d9fe4af', 'acf_load_field_drinks' );

/**
 * ACF load field drinks
 *
 * @param  array $field The field array containing all settings.
 * @return array $field
 */
function acf_load_field_drinks( array $field ) {
	$drinks = get_template_directory() . '/includes/data/drinks.json';

	$field['choices'] = json_decode( file_get_contents( $drinks ), true ); // phpcs:ignore

	return $field;
}


add_filter( 'acf/load_field/key=field_5e99e47c3461d', 'acf_load_field_type' );

/**
 * ACF load field type
 *
 * @param  array $field The field array containing all settings.
 *
 * @return array $field
 */
function acf_load_field_type( array $field ) {
	$types = get_template_directory() . '/includes/data/types.json';

	$field['choices'] = json_decode( file_get_contents( $types ), true ); // phpcs:ignore

	return $field;
}
