<?php // phpcs:ignore
/**
 * Send voucher
 *
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 * @package LeagleCup
 */

namespace DLAP;

use DLAP\{ Mail };
use Timber\{ Timber, Post };

/**
 * Class Send Voucher
 *
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */
class GenerateGiftCoupon {

	/**
	 * Drinks
	 *
	 * @var array
	 */
	public $drinks = array();

	/**
	 * Types
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_nopriv_request_gift_coupon', array( $this, 'ajax' ) );
		add_action( 'wp_ajax_request_gift_coupon', array( $this, 'ajax' ) );

		$this->drinks = json_decode( file_get_contents( get_template_directory() . '/includes/data/drinks.json' ), true ); // phpcs:ignore
		$this->types = json_decode( file_get_contents( get_template_directory() . '/includes/data/types.json' ), true ); // phpcs:ignore
	}


	/**
	 * Ajax
	 *
	 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
	 * @access public
	 */
	public function ajax() {
		check_ajax_referer( 'security', 'nonce' );

		$data = isset( $_POST ) ? $_POST : false;

		if ( ! $data ) {
			return;
		}

		$data['menu']['drinks'] = array(
			'cocktail_aperitif'             => isset( $data['menu']['cocktail_aperitif'] ) ? $data['menu']['cocktail_aperitif'] : false,
			'champagne_brut_maison_piollot' => isset( $data['menu']['champagne_brut_maison_piollot'] ) ? $data['menu']['champagne_brut_maison_piollot'] : false,
			'verre_de_vin'                  => isset( $data['menu']['verre_de_vin'] ) ? $data['menu']['verre_de_vin'] : false,
			'accord_mets_vins_2_verres_de_vin_1_boisson_chaude' => isset( $data['menu']['accord_mets_vins_2_verres_de_vin_1_boisson_chaude'] ) ? $data['menu']['accord_mets_vins_2_verres_de_vin_1_boisson_chaude'] : false,
		);

		$new_post = array(
			'post_title'  => $data['last_name'] . ' ' . $data['first_names'],
			'post_status' => 'pending',
			'post_type'   => 'coupon',
		);

		$pid = wp_insert_post( $new_post );

		add_post_meta( $pid, 'last_name', $data['last_name'], true );
		add_post_meta( $pid, 'first_names', $data['first_names'], true );
		add_post_meta( $pid, 'postal_code_city', $data['postal_code_city'], true );
		add_post_meta( $pid, 'address', $data['address'], true );
		add_post_meta( $pid, 'email', $data['email'], true );
		add_post_meta( $pid, 'type', $data['type'], true );
		add_post_meta( $pid, 'total', (float) $data['total'], true );

		if ( isset( $data['message'] ) ) {
			add_post_meta( $pid, 'message', $data['message'], true );
		}

		add_post_meta( $pid, 'menu_drinks', $data['menu']['drinks'], true );
		add_post_meta( $pid, 'menu_number', $data['menu']['number'], true );
		add_post_meta( $pid, 'menu_type', $data['menu']['type'], true );
		add_post_meta( $pid, 'menu_names_first_names', $data['menu']['names_first_names'], true );

		// Mail.
		$to[] = $data['email'];

		$headers[] = 'Reply-To: ' . $data['last_name'] . ' ' . $data['first_names'] . ' <' . $data['email'] . '>';
		$headers[] = 'From: De l\'île aux papilles <restaurant@ile-aux-papilles.fr>';
		$headers[] = 'Bcc: ' . get_option( 'admin_email' );
		$headers[] = 'Bcc: restaurant@ile-aux-papilles.fr';

		$context = Timber::get_context();

		$context['post'] = $data;

		foreach ( $data['menu']['drinks'] as $key => $value ) {
			$context['post']['menu']['drinks'][ $key ] = $this->drinks[ $key ];
		}

		$context['post']['menu']['type'] = $this->types[ $data['menu']['type'] ];

		Mail::init()
			->to( $to )
			->subject( 'Une nouvelle demande de coupon !' )
			->message( 'partials/email-confirm.html.twig', $context )
			->headers( $headers )
			->send();

		wp_die();
	}
}
