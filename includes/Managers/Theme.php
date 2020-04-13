<?php // phpcs:ignore
/**
 * Bootstraps WordPress theme related functions, most importantly enqueuing javascript and styles.
 *
 * @package DLAP
 * @subpackage DLAP/Managers/Theme
 */

namespace DLAP\Managers;

use Timber\{ Menu };
use DLAP\{ Helpers };
use Twig\{ TwigFunction };

/**
 * Theme
 */
class Theme {
	/**
	 * Managers
	 *
	 * @var array
	 */
	private $managers = array();

	/**
	 * The name of the theme
	 *
	 * @access private
	 * @var    string
	 */
	private $theme_name;


	/**
	 * The version of this theme
	 *
	 * @access private
	 * @var    string
	 */
	private $theme_version;


	/**
	 * The manifest of this theme
	 *
	 * @access private
	 * @var    array
	 */
	private $theme_manifest;

	/**
	 * Constructor
	 *
	 * @param string $theme_name    The theme name.
	 * @param string $theme_version The theme version.
	 * @param array  $managers Array of managers.
	 */
	public function __construct( string $theme_name, string $theme_version, array $managers ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;
		$this->managers      = $managers;

		$this->theme_manifest = Helpers::get_theme_manifest();

		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_filter( 'timber_context', array( $this, 'add_socials_to_context' ) );
		add_filter( 'timber_context', array( $this, 'add_manifest_to_context' ) );
		add_filter( 'timber_context', array( $this, 'add_menus_to_context' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );

	}


	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() {
		if ( count( $this->managers ) > 0 ) {
			foreach ( $this->managers as $manager ) {
				$manager->run();
			}
		}

		$this->add_theme_supports();
		$this->add_post_type_supports();
		$this->remove_post_type_supports();
		$this->register_menus();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * Add theme supports
	 *
	 * @return void
	 */
	public function add_theme_supports() : void {
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @see https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
	}


	/**
	 * Add post type supports
	 *
	 * @return void
	 */
	public function add_post_type_supports() {
		add_post_type_support( 'page', 'excerpt' );
	}


	/**
	 * Remove post type supports
	 *
	 * @return void
	 */
	public function remove_post_type_supports() {
		remove_post_type_support( 'page', 'editor' );
	}

	/**
	 * Add to Twig
	 *
	 * @param object $twig Twig environment.
	 * @return object $twig
	 * @access public
	 */
	public function add_to_twig( object $twig ) : object {
		$twig->addFunction(
			new TwigFunction(
				'html_class',
				function ( $args = '' ) {
					return Helpers::html_class( $args );
				}
			)
		);

		$twig->addFunction(
			new TwigFunction(
				'body_class',
				function ( $args = '' ) {
					return body_class( $args );
				}
			)
		);

		return $twig;
	}


	/**
	 * Add manifest to context
	 *
	 * @param array $context Timber context.
	 *
	 * @return array $context
	 */
	public function add_manifest_to_context( array $context ) : array {
		$context['manifest'] = $this->get_theme_manifest();

		return $context;
	}


	/**
	 * Add socials to context
	 *
	 * @param array $context Timber context.
	 * @return array
	 */
	public function add_socials_to_context( array $context ) : array {
		// Share and Socials links.
		$socials = array(
			array(
				'title' => 'Twitter',
				'slug'  => 'twitter',
				'name'  => __( 'Share on Twitter', 'delileauxpapilles' ),
				'link'  => 'https://twitter.com/intent/tweet?url=',
				'url'   => get_option( 'twitter' ),
			),
			array(
				'title' => 'Facebook',
				'slug'  => 'facebook',
				'name'  => __( 'Share on Facebook', 'delileauxpapilles' ),
				'link'  => 'https://www.facebook.com/sharer.php?u=',
				'url'   => get_option( 'facebook' ),
			),
			array(
				'title' => 'YouTube',
				'slug'  => 'youtube',
				'url'   => get_option( 'youtube' ),
			),
			array(
				'title' => 'LinkedIn',
				'slug'  => 'linkedin',
				'name'  => __( 'Share on LinkedIn', 'delileauxpapilles' ),
				'link'  => 'https://www.linkedin.com/sharing/share-offsite/?url=',
				'url'   => get_option( 'linkedin' ),
			),
		);

		foreach ( $socials as $social ) {
			if ( ! empty( $social['url'] ) ) {
				$context['socials'][ $social['slug'] ] = $social;
			}
			$context['shares'][ $social['slug'] ] = $social;
		}

		return $context;
	}


	/**
	 * Add to context
	 *
	 * @param  array $context Timber context
	 *
	 * @return array
	 */
	public function add_to_context( array $context ) : array {
		$context['feed_link']    = get_feed_link();
		$context['phone_number'] = get_option( 'phone_number' );

		return $context;
	}



	/**
	 * Add menus to context
	 *
	 * @param array $context Timber context.
	 * @return array
	 * @since  1.0.0
	 */
	public function add_menus_to_context( array $context ) : array {
		$menus = get_registered_nav_menus();

		foreach ( $menus as $menu => $value ) {
			$context['menus'][ $menu ] = new Menu( $menu );
		}

		return $context;
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

		wp_register_script(
			"{$this->theme_name}-main",
			get_template_directory_uri() . '/' . $this->get_theme_manifest()['main.js'],
			array(),
			$this->get_theme_version(),
			true
		);

		wp_localize_script(
			"{$this->theme_name}-main",
			'delileauxpapilles',
			array(
				'template_directory_uri' => get_template_directory_uri(),
				'base_url'               => site_url(),
				'home_url'               => home_url( '/' ),
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'api_url'                => home_url( 'wp-json' ),
				'current_url'            => get_permalink(),
				'nonce'                  => wp_create_nonce( 'security' ),
			)
		);

		wp_enqueue_script( "{$this->theme_name}-main" );
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
		foreach ( $this->get_webfonts() as $name => $url ) {
			wp_register_style( 'font-' . $name, $url, array(), '1.0.0' );
			$webfonts[] = "font-$name";
		}

		// Theme stylesheet.
		wp_register_style(
			$this->theme_name . '-main',
			get_template_directory_uri() . '/' . $this->get_theme_manifest()['main.css'],
			$webfonts,
			$this->get_theme_version()
		);

		wp_enqueue_style( "$this->theme_name-main" );
	}


	/**
	 * Register nav menus
	 *
	 * @return void
	 */
	public function register_menus() {
		register_nav_menus(
			array(
				'menu' => __( 'Menu', 'delileauxpapilles' ),
			)
		);
	}


	/**
	 * List webfonts used by the theme.
	 *
	 * @since  1.0.0
	 * @return array
	 * @access public
	 */
	public function get_webfonts() : array {
		return array();
	}


	/**
	 * Retrieve the manifest of the theme.
	 *
	 * @since  1.0.0
	 * @return array The manifest of the plugin.
	 * @access public
	 */
	private function get_theme_manifest() : array {
		return $this->theme_manifest;
	}


	/**
	 * Retrieve the version number of the theme.
	 *
	 * @since  1.0.0
	 * @return string The version number of the plugin.
	 */
	private function get_theme_version() : string {
		return $this->theme_version;
	}
}
