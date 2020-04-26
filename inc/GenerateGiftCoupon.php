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

		$this->drinks = get_field( 'drinks', 'coupon-options' );
		$this->types  = get_field( 'type', 'coupon-options' );
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
		add_post_meta( $pid, 'menu_number', $data['menu']['number'], true );
		add_post_meta( $pid, 'menu_names_first_names', $data['menu']['names_first_names'], true );

		if ( isset( $data['message'] ) ) {
			update_field( 'field_5e9a1e0115532', $data['message'], $pid );
		}

		$drinks = array();

		foreach ( $data['menu']['drinks'] as $key => $value ) {
			foreach ( $this->drinks as $drink ) {
				if ( $drink['value'] === $value ) {
					$drinks[] = array(
						'value' => $value,
						'label' => $drink['label'],
						'price' => $drink['price'],
					);
				}
			}
		}

		$types = array();

		foreach ( $this->types as $type ) {
			if ( $type['value'] === $data['menu']['type'] ) {
				$types[] = array(
					'value' => $data['menu']['type'],
					'label' => $type['label'],
					'price' => $type['price'],
				);
			}
		}

		// Menu field.
		update_field(
			'field_5e99e38a3461b',
			array(
				'field_5e99e4d9fe4af' => $drinks,
				'field_5e99e47c3461d' => $types,
			),
			$pid
		);

		// Mail.
		$to[] = $data['email'];

		$headers[] = 'Reply-To: ' . $data['last_name'] . ' ' . $data['first_names'] . ' <' . $data['email'] . '>';
		$headers[] = 'From: De l\'île aux papilles <restaurant@ile-aux-papilles.fr>';
		$headers[] = 'Bcc: ' . get_option( 'admin_email' );
		$headers[] = 'Bcc: restaurant@ile-aux-papilles.fr';

		$context = Timber::get_context();

		$context['post'] = new Post( $pid );

		Mail::init()
			->to( $to )
			->subject( __( 'A new coupon request!', 'delileauxpapilles' ) )
			->message( 'partials/email-confirm.html.twig', $context )
			->headers( $headers )
			->send();

		wp_die();
	}
}
