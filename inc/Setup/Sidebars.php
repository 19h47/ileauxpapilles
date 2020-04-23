<?php
/**
 * Sidebars
 *
 * @package dlap
 */

namespace DLAP\Setup;

 /**
  * Sidebars
  */
class Sidebars {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_action( 'widgets_init', array( $this, 'register' ) );
	}

	/**
	 * Register
	 *
	 * @return void
	 */
	public function register() : void {
		register_sidebar(
			array(
				'name'          => __( 'Footer Primary', 'delileauxpapilles' ),
				'id'            => 'footer_primary',
				'before_title'  => '<h3 class="Site-footer__title">',
				'after_title'   => '</h3>',
				'before_widget' => '',
				'after_widget'  => '',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Footer Secondary', 'delileauxpapilles' ),
				'id'            => 'footer_secondary',
				'before_title'  => '<h3 class="Site-footer__title">',
				'after_title'   => '</h3>',
				'before_widget' => '',
				'after_widget'  => '',
			)
		);
	}
}

