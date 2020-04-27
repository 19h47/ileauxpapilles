<?php // phpcs:ignore
/**
 * ACF
 *
 * @package dlap
 */

namespace DLAP\Plugins;

/**
 * Acf
 */
class Acf {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		// on init and not on acf/init because of custom post type register
		add_action( 'init', array( $this, 'add_coupon_options_page' ), 0 );
	}

	/**
	 * Add coupon options page
	 *
	 * @return void
	 */
	public function add_coupon_options_page() {

		acf_add_options_sub_page(
			array(
				'menu_title'  => _x( 'Coupon Settings', 'gift coupon', 'delileauxpapilles' ),
				'page_title'  => _x( 'Coupon Settings', 'gift coupon', 'delileauxpapilles' ),
				'menu_slug'   => 'coupon-settings',
				'parent_slug' => 'edit.php?post_type=coupon',
				'capability'  => 'edit_posts',
				'post_id'     => 'coupon-options',
			)
		);

		acf_add_options_sub_page(
			array(
				'menu_title'  => _x( 'Email Settings', 'gift coupon', 'delileauxpapilles' ),
				'page_title'  => _x( 'Email Settings', 'gift coupon', 'delileauxpapilles' ),
				'menu_slug'   => 'email-settings',
				'parent_slug' => 'edit.php?post_type=coupon',
				'capability'  => 'edit_posts',
				'post_id'     => 'email-options',
			)
		);
	}
}





