<?php
/**
 * Custom template tags for this theme
 *
 * Additional features to allow styling of the templates.
 *
 * PHP version 7.2.10
 *
 * @package  VacancesPourSeniors
 * @since    1.0.0
 * @license
 */

add_filter( 'body_class', 'custom_body_class' );

/**
 * Adds custom classes to the array of body classes.
 *
 * Displays the class names for the body element.
 *
 * @param array $classes Space-separated string or array of class names to add to the class list.
 * @return array
 */
function custom_body_class( $classes ) {
	// Home.
	if ( is_front_page() ) {
		$classes[] = 'Front-page';
	}

	if ( ! is_front_page() ) {
		$classes[] = 'Page';
	}

	if ( is_single() ) {
		$classes[] = 'Single';
	}

	if ( is_archive() ) {
		$classes[] = 'Archive';
	}

	return $classes;
}
