<?php // phpcs:ignore
/**
 * UNI helpers class
 *
 * @package DLAP
 * @subpackage DLAP/Helpers
 */

namespace DLAP;

/**
 * Helpers class
 */
class Helpers {

	/**
	 * Get theme manifest
	 *
	 * @return bool|array
	 */
	public static function get_theme_manifest() {
		$file = get_template_directory() . '/dist/manifest.json';

		return json_decode( file_get_contents( $file ), true ); // phpcs:ignore
	}


	/**
	 * Retrieve the classes for the html element as an array.
	 *
	 * @param  string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 * @access public
	 */
	private static function get_html_class( $class = '' ) : array {
		$classes = array();
		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}
		$classes = array_map( 'esc_attr', $classes );
		/**
		 * Filter the list of CSS html classes for the current post or page.
		 *
		 * @param array  $classes An array of html classes.
		 * @param string $class   A comma-separated list of additional classes added to the html.
		 */
		$classes = apply_filters( 'html_class', $classes, $class );

		return array_unique( $classes );
	}


	/**
	 * Display the classes for the html element.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return string
	 */
	public static function html_class( $class = '' ) : string {
		// Separates classes with a single space, collates classes for html element.
		return 'class="' . join( ' ', self::get_html_class( $class ) ) . '"';
	}
}
