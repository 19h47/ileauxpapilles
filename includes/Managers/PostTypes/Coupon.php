<?php // phpcs:ignore
/**
 * Coupon
 *
 * @package DLAP
 */

namespace DLAP\Managers\PostTypes;

/**
 * Coupon
 */
class Coupon {

	/**
	 * Run function
	 *
	 * @access public
	 * @return void
	 */
	public function run() {
		add_action( 'init', array( $this, 'register_post_type' ), 10, 0 );
		add_action( 'template_redirect', array( $this, 'hide' ) );

		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ), 10, 1 );
	}


	/**
	 * Hide
	 *
	 * @return void
	 */
	public function hide() {

		if ( is_singular( 'coupon' ) && ! current_user_can( 'administrator' ) ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}
	}


	/**
	 * Coupon updated messages function
	 *
	 * @param array $messages Post updated messages. For defaults see $messages declarations above.
	 * @return array $message
	 * @link https://developer.wordpress.org/coupon/hooks/post_updated_messages/
	 * @access public
	 */
	public function updated_messages( array $messages ) : array {
		global $post;

		$post_ID     = isset( $post_ID ) ? (int) $post_ID : 0;
		$preview_url = get_preview_post_link( $post );

		/* translators: Publish box date format, see https://secure.php.net/date */
		$scheduled_date = date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) );

		$view_link_html = sprintf(
			' <a href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'View Coupon', 'delileauxpapilles' )
		);

		$scheduled_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'Preview Coupon', 'delileauxpapilles' )
		);

		$preview_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( $preview_url ),
			__( 'Preview Coupon', 'delileauxpapilles' )
		);

		$messages['agency'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Coupon updated.', 'delileauxpapilles' ) . $view_link_html,
			2  => __( 'Custom field updated.', 'delileauxpapilles' ),
			3  => __( 'Custom field deleted.', 'delileauxpapilles' ),
			4  => __( 'Coupon updated.', 'delileauxpapilles' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Coupon restored to revision from %s.', 'delileauxpapilles' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore
			6  => __( 'Coupon published.', 'delileauxpapilles' ) . $view_link_html,
			7  => __( 'Coupon saved.', 'delileauxpapilles' ),
			8  => __( 'Coupon submitted.', 'delileauxpapilles' ) . $preview_link_html,
			9  => sprintf( __( 'Coupon scheduled for: %s.', 'delileauxpapilles' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_link_html, // phpcs:ignore
			10 => __( 'Coupon draft updated.', 'delileauxpapilles' ) . $preview_link_html,
		);

		return $messages;
	}


	/**
	 * Register Custom Post Type
	 *
	 * @return void
	 * @access public
	 */
	public function register_post_type() : void {
		$labels = array(
			'name'                     => _x( 'Coupons', 'coupon general name', 'delileauxpapilles' ),
			'singular_name'            => _x( 'Coupon', 'coupon singular name', 'delileauxpapilles' ),
			'add_new'                  => _x( 'Add New', 'coupon', 'delileauxpapilles' ),
			'add_new_item'             => __( 'Add New Coupons', 'delileauxpapilles' ),
			'edit_item'                => __( 'Edit Coupon', 'delileauxpapilles' ),
			'new_item'                 => __( 'New Coupon', 'delileauxpapilles' ),
			'view_item'                => __( 'View Coupon', 'delileauxpapilles' ),
			'view_items'               => __( 'View Coupons', 'delileauxpapilles' ),
			'search_items'             => __( 'Search Coupons', 'delileauxpapilles' ),
			'not_found'                => __( 'No coupons found.', 'delileauxpapilles' ),
			'not_found_in_trash'       => __( 'No coupons found in Trash.', 'delileauxpapilles' ),
			'parent_item_colon'        => __( 'Parent Coupon:', 'delileauxpapilles' ),
			'all_items'                => __( 'All coupons', 'delileauxpapilles' ),
			'archives'                 => __( 'Coupon Archives', 'delileauxpapilles' ),
			'attributes'               => __( 'Coupon Attributes', 'delileauxpapilles' ),
			'insert_into_item'         => __( 'Insert into coupon', 'delileauxpapilles' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this coupon', 'delileauxpapilles' ),
			'featured_image'           => _x( 'Featured Image', 'coupon', 'delileauxpapilles' ),
			'set_featured_image'       => _x( 'Set featured image', 'coupon', 'delileauxpapilles' ),
			'remove_featured_image'    => _x( 'Remove featured image', 'coupon', 'delileauxpapilles' ),
			'use_featured_image'       => _x( 'Use as featured image', 'coupon', 'delileauxpapilles' ),
			'filter_items_list'        => __( 'Filter coupons list', 'delileauxpapilles' ),
			'items_list_navigation'    => __( 'Coupons list navigation', 'delileauxpapilles' ),
			'items_list'               => __( 'Coupons list', 'delileauxpapilles' ),
			'item_published'           => __( 'Coupon published.', 'delileauxpapilles' ),
			'item_published_privately' => __( 'Coupon published privately.', 'delileauxpapilles' ),
			'item_reverted_to_draft'   => __( 'Coupon reverted to draft.', 'delileauxpapilles' ),
			'item_scheduled'           => __( 'Coupon scheduled.', 'delileauxpapilles' ),
			'item_updated'             => __( 'Coupon updated.', 'delileauxpapilles' ),
		);

		$args = array(
			'label'               => __( 'Coupon', 'delileauxpapilles' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields' ),
			'taxonomies'          => array(),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-tickets-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'map_meta_cap'        => true,
			'show_in_rest'        => false,
		);
		register_post_type( 'coupon', $args );
	}
}
