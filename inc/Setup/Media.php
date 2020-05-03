<?php // phpcs:ignore
/**
 * Media
 *
 * @package dlap
 */

namespace DLAP\Setup;

/**
 * Menus
 */
class Media {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_filter( 'get_image_tag_class', array( $this, 'set_image_class' ), 10, 1 );

	}


	/**
	 * Set image class
	 *
	 * @param string $class CSS class name or space-separated list of classes.
	 *
	 * @return string $class
	 */
	public function set_image_class( string $class ) {
		return $class . ' js-baseline';
	}
}
