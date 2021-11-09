<?php // phpcs:ignore
/**
 * Enqueue
 *
 * @package dlap
 */

namespace DLAP\Setup;

use Timber\{ Timber };

/**
 * Enqueue
 */
class Enqueue {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'preload' ) );
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

		wp_deregister_script( 'jquery' );

		wp_enqueue_script( // phpcs:ignore
			get_theme_text_domain() . '-vendors',
			get_template_directory_uri() . '/' . get_theme_manifest()['vendors.js'],
			array(),
			null,
			true
		);

		wp_register_script( // phpcs:ignore
			get_theme_text_domain() . '-main',
			get_template_directory_uri() . '/' . get_theme_manifest()['main.js'],
			array( get_theme_text_domain() . '-vendors' ),
			null,
			true
		);

		wp_add_inline_script(
			get_theme_text_domain() . '-main',
			'var ileauxpapilles = ' . json_encode(
				array(
					'template_directory_uri' => get_template_directory_uri(),
					'base_url'               => site_url(),
					'home_url'               => home_url( '/' ),
					'ajax_url'               => admin_url( 'admin-ajax.php' ),
					'api_url'                => home_url( 'wp-json' ),
					'current_url'            => get_permalink(),
					'nonce'                  => wp_create_nonce( 'security' ),
					'popupContent'           => Timber::compile( 'partials/popupcontent.html.twig', Timber::get_context() ),
					'coordinates'            => array(
						'latitude'  => get_option( 'latitude' ),
						'longitude' => get_option( 'longitude' ),
					),
				)
			),
			'before',
		);

		wp_register_script( get_theme_text_domain() . '-feature', false );
		wp_add_inline_script( get_theme_text_domain() . '-feature', 'window.isMobile=/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)||1<navigator.maxTouchPoints;!function(e,n,o){("ontouchstart"in e||e.DocumentTouch&&n instanceof DocumentTouch||o.MaxTouchPoints>0||o.msMaxTouchPoints>0)&&(n.documentElement.className=n.documentElement.className.replace(/\bno-touch\b/,"touch")),n.documentElement.className=n.documentElement.className.replace(/\bno-js\b/,"js")}' );

		wp_enqueue_script( get_theme_text_domain() . '-feature' );
		wp_enqueue_script( get_theme_text_domain() . '-main' );
	}



	public function dequeue_styles() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
	}


	/**
	 * Enqueue styles.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueue_style() : void {

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


	/**
	 * Preload
	 */
	function preload() {
		global $wp_scripts, $wp_styles;

		// Scripts
		foreach ( $wp_scripts->queue as $handle ) {
			$script = $wp_scripts->registered[ $handle ];

			if ( substr( $script->handle, 0, strlen( get_theme_text_domain() ) ) !== get_theme_text_domain() ) {
				continue;
			}

			if ( isset( $script->extra['group'] ) && 1 === $script->extra['group'] ) {
				$href = $script->src . ( $script->ver ? "?ver={$script->ver}" : '' );
				echo '<link rel="preload" as="script" href="' . $href . '">';
			}
		}

		// Styles
		foreach ( $wp_styles->queue as $handle ) {
			$style = $wp_styles->registered[ $handle ];

			if ( substr( $style->handle, 0, strlen( get_theme_text_domain() ) ) !== get_theme_text_domain() ) {
				continue;
			}

			$href = $style->src . ( $style->ver ? "?ver={$style->ver}" : '' );
			echo '<link rel="preload" as="style" href="' . $href . '">';

		}

		// Fonts
		foreach ( get_theme_manifest() as $key => $value ) {
			if ( substr( $key, 0, 6 ) === 'fonts/' ) {
				$extension = pathinfo( $key, PATHINFO_EXTENSION );
				$href      = get_template_directory_uri() . '/' . $value;

				echo '<link rel="preload" as="font" href="' . $href . '" type="font/' . $extension . '" crossorigin>';
			}
		}
	}
}
