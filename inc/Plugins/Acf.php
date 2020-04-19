<?php
/**
 * ACF
 *
 * @package dlap
 */

namespace DLAP\Plugins;

class Acf {
	
	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_filter( 'acf/load_field/key=field_5e99e4d9fe4af', array( $this, 'acf_load_field_drinks' ) );
		add_filter( 'acf/load_field/key=field_5e99e47c3461d', array( $this, 'acf_load_field_type' ) );
	}
	
	/**
	 * ACF load field drinks
	 *
	 * @param  array $field The field array containing all settings.
	 * @return array $field
	 */
	function acf_load_field_drinks( array $field ) : array {
		$drinks = get_template_directory() . '/inc/data/drinks.json';
	
		$field['choices'] = json_decode( file_get_contents( $drinks ), true ); // phpcs:ignore
	
		return $field;
	}
	
	
	/**
	 * ACF load field type
	 *
	 * @param  array $field The field array containing all settings.
	 *
	 * @return array $field
	 */
	function acf_load_field_type( array $field ) : array {
		$types = get_template_directory() . '/inc/data/types.json';
	
		$field['choices'] = json_decode( file_get_contents( $types ), true ); // phpcs:ignore
	
		return $field;
	}
}





