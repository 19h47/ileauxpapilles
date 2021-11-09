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
				'name'  => __( 'Share on Twitter', 'ileauxpapilles' ),
				'link'  => 'https://twitter.com/intent/tweet?url=',
				'url'   => get_option( 'twitter' ),
			),
			array(
				'title' => 'Facebook',
				'slug'  => 'facebook',
				'name'  => __( 'Share on Facebook', 'ileauxpapilles' ),
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
				'name'  => __( 'Share on LinkedIn', 'ileauxpapilles' ),
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
		$context['feed_link']              = get_feed_link();
		$context['phone_number']           = get_option( 'phone_number' );
		$context['address']                = get_option( 'address' );
		$context['latitude']               = get_option( 'latitude' );
		$context['longitude']              = get_option( 'longitude' );
		$context['alert_message']          = get_option( 'alert_message' );
		$context['legal_notice_permalink'] = get_permalink( get_theme_mod( 'legal_notice_permalink' ) );
		$context['gift_coupon_permalink']  = get_permalink( get_theme_mod( 'gift_coupon_permalink' ) );

		$context['footer_primary']   = Timber::get_widgets( 'footer_primary' );
		$context['footer_secondary'] = Timber::get_widgets( 'footer_secondary' );

		$context['references'] = Timber::get_sidebar( 'component-references.php' );
		$context['partners']   = Timber::get_sidebar( 'component-partners.php' );
		$context['modal']      = Timber::get_sidebar( 'component-modal.php' );

		$context['placeholders'] = array(
			'last_name'                  => _x( 'Last name', 'gift coupon', 'ileauxpapilles' ),
			'first_names'                => _x( 'First name(s)', 'gift coupon', 'ileauxpapilles' ),
			'email'                      => _x( 'E-mail', 'gift coupon', 'ileauxpapilles' ),
			'address'                    => _x( 'Address', 'gift coupon', 'ileauxpapilles' ),
			'postal_code_and_city'       => _x( 'Postal code and city', 'gift coupon', 'ileauxpapilles' ),
			'lastnames_and_firstnames'   => _x( 'Lastname(s) and firstname(s)', 'gift coupon', 'ileauxpapilles' ),
			'your_message'               => _x( 'Your message', 'gift coupon', 'ileauxpapilles' ),
			'your_personnalized_message' => _x( 'Your personalized message', 'gift coupon', 'ileauxpapilles' ),
		);

		$context['coupon_fields']['drinks'] = get_field( 'drinks', 'coupon-options' );
		$context['coupon_fields']['type']   = get_field( 'type', 'coupon-options' );
		$context['coupon_fields']['labels'] = get_field( 'labels', 'coupon-options' );
		$context['email_fiels']['content']  = get_field( 'content', 'email-options' );

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
