<?php
/**
 * Enqueue
 * 
 * @package dlap
 */

namespace DLAP\Setup;

use Dotenv\{ Dotenv };

$dotenv = Dotenv::createImmutable( get_template_directory() );
$dotenv->load();

class Enqueue {
     	
	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }
    
    /**
	 * Enqueue scripts
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueue_scripts() : void {
		wp_deregister_script( 'wp-embed' );

		if ( 'true' === getenv( 'PRODUCTION' ) ) {
			wp_deregister_script( 'jquery' );
		}

		wp_register_script( // phpcs:ignore
			get_theme_name() . '-main',
			get_template_directory_uri() . '/' . get_theme_manifest()['main.js'],
			array(),
			false,
			true
		);

		wp_localize_script(
			get_theme_name() . '-main',
			'delileauxpapilles',
			array(
				'template_directory_uri' => get_template_directory_uri(),
				'base_url'               => site_url(),
				'home_url'               => home_url( '/' ),
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'api_url'                => home_url( 'wp-json' ),
				'current_url'            => get_permalink(),
				'nonce'                  => wp_create_nonce( 'security' ),
				'popupContent'           => nl2br( get_option( 'address' ) ),
				'coordinates'            => array(
					'latitude'  => get_option( 'latitude' ),
					'longitude' => get_option( 'longitude' ),
				),
			)
		);

		wp_enqueue_script( get_theme_name() . '-main' );
	}


	/**
	 * Enqueue styles.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueue_style() : void {

		wp_dequeue_style( 'wp-block-library' );

		// Add custom fonts, used in the main stylesheet.
		$webfonts = array();
		foreach ( get_webfonts() as $name => $url ) {
			wp_register_style( 'font-' . $name, $url, array(), '1.0.0' );
			$webfonts[] = "font-$name";
		}

		// Theme stylesheet.
		wp_register_style( // phpcs:ignore
			get_theme_name() . '-main',
			get_template_directory_uri() . '/' . get_theme_manifest()['main.css'],
			$webfonts,
			false
		);

		wp_enqueue_style( get_theme_name() . '-main' );
	}
}