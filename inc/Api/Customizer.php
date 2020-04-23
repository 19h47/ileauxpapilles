<?php
/**
 * Customizer
 *
 * @category Customizer
 * @package  dlap
 * @author   Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace DLAP\Api;

use WP_Customize_Manager;

/**
 * Contact
 */
class Customizer {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'customize_register', array( $this, 'register' ), 10, 1 );
	}


	/**
	 * Register
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	public function register( WP_Customize_Manager $wp_customize ) {
		// Add contact section.
		$wp_customize->add_section(
			'contact',
			array(
				'title'       => __( 'Contact', 'delileauxpapilles' ),
				'description' => __( 'Contact settings', 'delileauxpapilles' ),
			)
		);

		// Facebook.
		$wp_customize->add_setting(
			'facebook',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'facebook',
			array(
				'label'       => __( 'Facebook', 'delileauxpapilles' ),
				'description' => __( 'Facebook URL', 'delileauxpapilles' ),
				'section'     => 'contact',
				'settings'    => 'facebook',
			)
		);

		// Phone number.
		$wp_customize->add_setting(
			'phone_number',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'phone_number',
			array(
				'label'       => __( 'Phone number', 'delileauxpapilles' ),
				'section'     => 'contact',
				'settings'    => 'phone_number',
			)
		);

		// LinkedIn.
		$wp_customize->add_setting(
			'linkedin',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'youtube',
			array(
				'label'       => __( 'LinkedIn', 'delileauxpapilles' ),
				'description' => __( 'LinkedIn URL', 'delileauxpapilles' ),
				'section'     => 'contact',
				'settings'    => 'linkedin',
			)
		);

		// Latitude.
		$wp_customize->add_setting(
			'latitude',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'latitude',
			array(
				'label'    => __( 'Latitude', 'delileauxpapilles' ),
				'section'  => 'contact',
				'settings' => 'latitude',
			)
		);

		// Longitude.
		$wp_customize->add_setting(
			'longitude',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'email',
			array(
				'label'    => __( 'Longitude', 'delileauxpapilles' ),
				'section'  => 'contact',
				'settings' => 'longitude',
			)
		);

		// Address.
		$wp_customize->add_setting(
			'address',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'address',
			array(
				'label'    => __( 'Address', 'delileauxpapilles' ),
				'type'     => 'textarea',
				'section'  => 'contact',
				'settings' => 'address',
			)
		);

		//
		$wp_customize->add_setting(
			'legal_notice_permalink',
			array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'legal_notice_permalink',
			array(
				'type'    => 'dropdown-pages',
				'section' => 'contact',
				'label'   => _x( 'Legal Notice Page', 'Customizer', 'delileauxpapilles' ),
				'settings' => 'legal_notice_permalink',
			)
		);

		//
		$wp_customize->add_setting(
			'gift_coupon_permalink',
			array(
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'gift_coupon_permalink',
			array(
				'type'     => 'dropdown-pages',
				'section'  => 'contact',
				'label'    => _x( 'Gift Coupon Page', 'Customizer', 'delileauxpapilles' ),
				'settings' => 'gift_coupon_permalink',
			)
		);

		// Alert Message.
		$wp_customize->add_setting(
			'alert_message',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'alert_message',
			array(
				'type'     => 'textarea',
				'section'  => 'contact',
				'label'    => _x( 'Alert Message', 'Customizer', 'delileauxpapilles' ),
				'settings' => 'alert_message',
			)
		);
	}
}
