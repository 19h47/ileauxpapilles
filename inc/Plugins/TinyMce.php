<?php // phpcs:ignore
/**
 * TinyMCE
 *
 * @package dlap
 */

namespace DLAP\Plugins;

/**
 * TinyMCE
 */
class TinyMCE {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() : void {
		add_filter( 'tiny_mce_before_init', array( $this, 'add_colors' ), 10, 2 );
		add_filter( 'tiny_mce_plugins', array( $this, 'remove_custom_colors' ), 10, 2 );
		add_filter( 'tiny_mce_before_init', array( $this, 'block_formats' ) );
	}

	/**
	 * Customize the default color palette for TinyMce editor
	 *
	 * @param  array  $mceInit An array with TinyMCE config.
	 * @param  string $editor_id Unique editor identifier, e.g. 'content'. Accepts 'classic-block' when called from block editor's Classic block.
	 *
	 * @return array $mceInit
	 */
	public function add_colors( array $mceInit, string $editor_id ) : array { // phpcs:ignore
		$mceInit['textcolor_map'] = wp_json_encode( // phpcs:ignore
			array(
				'4b858e',
				'cyan, dark moderate',
			)
		);

		$mceInit['textcolor_rows'] = 1; // phpcs:ignore

		return $mceInit; // phpcs:ignore
	}



	/**
	 * Remove color picker from TinyMCE
	 *
	 * Remove the Color Picker plugin from TinyMCE. This will
	 * prevent users from adding custom colors. Note, the default color
	 * palette is still available (and customizable by developers) via
	 * textcolor_map using the tiny_mce_before_init hook.
	 *
	 * @param array  $plugins An array of default TinyMCE plugins.
	 * @param string $editor_id Unique editor identifier, e.g. 'content'. Accepts 'classic-block' when called from block editor's Classic block.
	 *
	 * @see https://wordpress.stackexchange.com/a/272174/115070
	 * @return array $plugins
	 */
	public function remove_custom_colors( array $plugins, string $editor_id ) : array {

		foreach ( $plugins as $key => $plugin ) {
			if ( 'colorpicker' === $plugin ) {
				unset( $plugins[ $key ] );
				return $plugins;
			}
		}

		return $plugins;
	}


	/**
	 * Block formats
	 *
	 * @return array $settings
	 */
	public function block_formats( $settings ) {
		$settings['block_formats'] = 'Paragraph=p;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;';

		return $settings;
	}
}


