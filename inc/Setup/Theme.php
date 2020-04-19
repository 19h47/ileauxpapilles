<?php // phpcs:ignore
/**
 * Bootstraps WordPress theme related functions, most importantly enqueuing javascript and styles.
 *
 * @package DLAP
 * @subpackage DLAP/Managers/Theme
 */

namespace DLAP\Setup;

use Timber\{ Timber, Menu };
use DLAP\{ GenerateGiftCoupon };
use Twig\{ TwigFunction };

$timber = new Timber();

Timber::$dirname = array( 'views', 'templates', 'dist' );


/**
 * Theme
 */
class Theme {

	/**
	 * The manifest of this theme
	 *
	 * @access private
	 * @var    array
	 */
	private $theme_manifest;


	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		$this->theme_manifest = get_theme_manifest();

		new GenerateGiftCoupon();

		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_filter( 'timber_context', array( $this, 'add_socials_to_context' ) );
		add_filter( 'timber_context', array( $this, 'add_manifest_to_context' ) );
		add_filter( 'timber_context', array( $this, 'add_menus_to_context' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );

		add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
		add_action( 'after_setup_theme', array( $this, 'add_post_type_supports' ) );
		add_action( 'after_setup_theme', array( $this, 'add_theme_textdomain' ) );
		add_action( 'init', array( $this, 'remove_post_type_supports' ) );
	}


	/**
	 * Add theme textdomain
	 *
	 * @return void
	 */
	public function add_theme_textdomain() : void {
		load_theme_textdomain( 'delileauxpapilles', get_template_directory() . '/languages' );
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
	public function add_post_type_supports() : void {
		add_post_type_support( 'page', 'excerpt' );
	}


	/**
	 * Remove post type supports
	 *
	 * @return void
	 */
	public function remove_post_type_supports() : void {
		remove_post_type_support( 'page', 'editor' );
	}

	/**
	 * Add to Twig
	 *
	 * @param object $twig Twig environment.
	 * @access public
	 *
	 * @return object $twig
	 */
	public function add_to_twig( object $twig ) : object {
		$twig->addFunction(
			new TwigFunction(
				'html_class',
				function ( $args = '' ) {
					return html_class( $args );
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
		$context['manifest'] = get_theme_manifest();

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
	 * @param  array $context Timber context.
	 *
	 * @return array
	 */
	public function add_to_context( array $context ) : array {
		$context['feed_link']    = get_feed_link();
		$context['phone_number'] = get_option( 'phone_number' );
		$context['references']   = Timber::get_sidebar( 'component-references.php' );
		$context['partners']     = Timber::get_sidebar( 'component-partners.php' );

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
}
