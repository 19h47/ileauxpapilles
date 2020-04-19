<?php // phpcs:ignore
/**
 * Menus
 *
 * @package dlap
 */

namespace DLAP\Setup;

/**
 * Menus
 */
class Menus {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );

	}

	/**
	 * Register nav menus
	 *
	 * @return void
	 */
	public function register_menus() : void {
		register_nav_menus(
			array(
				'menu'   => __( 'Menu', 'delileauxpapilles' ),
				'footer' => __( 'Footer', 'delileauxpapilles' ),
			)
		);
	}
}
