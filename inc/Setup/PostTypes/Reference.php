<?php // phpcs:ignore
/**
 * Reference
 *
 * @package dlap
 */

namespace DLAP\Setup\PostTypes;

use WP_Post;

/**
 * Reference
 */
class Reference {

	/**
	 * Run function
	 *
	 * @access public
	 * @return void
	 */
	public function run() {
		add_action( 'init', array( $this, 'register_post_type' ), 10, 0 );
		add_action( 'admin_head', array( $this, 'css' ) );
		add_action( 'save_post_reference', array( $this, 'save' ), 10, 3 );
		add_action( 'template_redirect', array( $this, 'hide' ) );

		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ), 10, 1 );
		add_action( 'manage_reference_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
		add_filter( 'manage_reference_posts_columns', array( $this, 'add_custom_columns' ) );
	}


	/**
	 * Hide
	 *
	 * @return void
	 */
	public function hide() {

		if ( is_singular( 'reference' ) ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}
	}

	/**
	 * Save
	 *
	 * @param  int     $post_id The post id.
	 * @param  WP_Post $post    The post object.
	 * @param  bool    $update  Is update or not.
	 * @return void
	 */
	public function save( int $post_id, WP_Post $post, bool $update ) : void {
		delete_transient( 'delileauxpapilles_references' );
	}


	/**
	 * CSS
	 *
	 * @return bool
	 */
	public function css() : bool {
		global $typenow;

		if ( 'reference' !== $typenow ) {
			return false;
		}

		?>
		<style>
			.fixed .column-thumbnail {
				vertical-align: top;
				width: 80px;
			}

			.column-thumbnail a {
				display: block;
			}
			.column-thumbnail a img {
				display: inline-block;
				vertical-align: middle;
				width: 80px;
				height: 80px;
				object-fit: contain;
				object-position: center;
			}
		</style>
		<?php

		return true;
	}


	/**
	 * Add custom columns
	 *
	 * @param array $columns Array of columns.
	 * @return array $new_columns
	 * @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
	 */
	public function add_custom_columns( array $columns ) : array {
		$new_columns = array();

		unset( $columns['date'] );

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['thumbnail'] = __( 'Thumbnail', 'delileauxpapilles' );
			}

			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @param string $column_name The column name.
	 * @param int    $post_id The ID of the post.
	 * @link https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
	 *
	 * @return void
	 */
	public function render_custom_columns( string $column_name, int $post_id ) : void {
		switch ( $column_name ) {
			case 'thumbnail':
				$thumbnail = get_the_post_thumbnail( $post_id, 'full' );
				$html      = '—';

				if ( $thumbnail ) {
					$html  = '<a href="' . esc_attr( get_edit_post_link( $post_id ) ) . '">';
					$html .= $thumbnail;
					$html .= '</a>';

					echo wp_kses_post( $html );
				} else {
					echo wp_kses_post( $html );
				}

				break;
		}
	}


	/**
	 * Reference updated messages function
	 *
	 * @param array $messages Post updated messages. For defaults see $messages declarations above.
	 * @return array $message
	 * @link https://developer.wordpress.org/reference/hooks/post_updated_messages/
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
			__( 'View Reference', 'delileauxpapilles' )
		);

		$scheduled_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_ID ) ),
			__( 'Preview Reference', 'delileauxpapilles' )
		);

		$preview_link_html = sprintf(
			' <a target="_blank" href="%1$s">%2$s</a>',
			esc_url( $preview_url ),
			__( 'Preview Reference', 'delileauxpapilles' )
		);

		$messages['agency'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Reference updated.', 'delileauxpapilles' ) . $view_link_html,
			2  => __( 'Custom field updated.', 'delileauxpapilles' ),
			3  => __( 'Custom field deleted.', 'delileauxpapilles' ),
			4  => __( 'Reference updated.', 'delileauxpapilles' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Reference restored to revision from %s.', 'delileauxpapilles' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore
			6  => __( 'Reference published.', 'delileauxpapilles' ) . $view_link_html,
			7  => __( 'Reference saved.', 'delileauxpapilles' ),
			8  => __( 'Reference submitted.', 'delileauxpapilles' ) . $preview_link_html,
			9  => sprintf( __( 'Reference scheduled for: %s.', 'delileauxpapilles' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_link_html, // phpcs:ignore
			10 => __( 'Reference draft updated.', 'delileauxpapilles' ) . $preview_link_html,
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
			'name'                     => _x( 'References', 'reference general name', 'delileauxpapilles' ),
			'singular_name'            => _x( 'Reference', 'reference singular name', 'delileauxpapilles' ),
			'add_new'                  => _x( 'Add New', 'reference', 'delileauxpapilles' ),
			'add_new_item'             => __( 'Add New References', 'delileauxpapilles' ),
			'edit_item'                => __( 'Edit Reference', 'delileauxpapilles' ),
			'new_item'                 => __( 'New Reference', 'delileauxpapilles' ),
			'view_item'                => __( 'View Reference', 'delileauxpapilles' ),
			'view_items'               => __( 'View References', 'delileauxpapilles' ),
			'search_items'             => __( 'Search References', 'delileauxpapilles' ),
			'not_found'                => __( 'No references found.', 'delileauxpapilles' ),
			'not_found_in_trash'       => __( 'No references found in Trash.', 'delileauxpapilles' ),
			'parent_item_colon'        => __( 'Parent Reference:', 'delileauxpapilles' ),
			'all_items'                => __( 'All references', 'delileauxpapilles' ),
			'archives'                 => __( 'Reference Archives', 'delileauxpapilles' ),
			'attributes'               => __( 'Reference Attributes', 'delileauxpapilles' ),
			'insert_into_item'         => __( 'Insert into reference', 'delileauxpapilles' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this reference', 'delileauxpapilles' ),
			'featured_image'           => _x( 'Featured Image', 'reference', 'delileauxpapilles' ),
			'set_featured_image'       => _x( 'Set featured image', 'reference', 'delileauxpapilles' ),
			'remove_featured_image'    => _x( 'Remove featured image', 'reference', 'delileauxpapilles' ),
			'use_featured_image'       => _x( 'Use as featured image', 'reference', 'delileauxpapilles' ),
			'filter_items_list'        => __( 'Filter references list', 'delileauxpapilles' ),
			'items_list_navigation'    => __( 'References list navigation', 'delileauxpapilles' ),
			'items_list'               => __( 'References list', 'delileauxpapilles' ),
			'item_published'           => __( 'Reference published.', 'delileauxpapilles' ),
			'item_published_privately' => __( 'Reference published privately.', 'delileauxpapilles' ),
			'item_reverted_to_draft'   => __( 'Reference reverted to draft.', 'delileauxpapilles' ),
			'item_scheduled'           => __( 'Reference scheduled.', 'delileauxpapilles' ),
			'item_updated'             => __( 'Reference updated.', 'delileauxpapilles' ),
		);

		$args = array(
			'label'               => __( 'Reference', 'delileauxpapilles' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-awards',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'map_meta_cap'        => true,
			'show_in_rest'        => false,
		);
		register_post_type( 'reference', $args );
	}
}
