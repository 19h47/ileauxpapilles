<?php
/**
 * TinyMCE
 *
 * @package DLAP
 */

add_filter( 'tiny_mce_before_init', 'add_colors', 10, 2 );

/**
 * Customize the default color palette for TinyMce editor
 *
 * @param  array $mceInit An array with TinyMCE config.
 * @param  string $editor_id Unique editor identifier, e.g. 'content'. Accepts 'classic-block' when called from block editor's Classic block.
 *
 * @return array $mceInit
 */
function add_colors( array $mceInit, string $editor_id ) : array { // phpcs:ignore
	$mceInit['textcolor_map'] = wp_json_encode( // phpcs:ignore
		array(
			'4b858e',
			'cyan, dark moderate',
		)
	);

	$mceInit['textcolor_rows'] = 1;

	return $mceInit; // phpcs:ignore
}


add_filter( 'tiny_mce_plugins', 'remove_custom_colors', 10, 2 );

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
function remove_custom_colors( array $plugins, string $editor_id ) {

	foreach ( $plugins as $key => $plugin ) {
		if ( 'colorpicker' === $plugin ) {
			unset( $plugins[ $key ] );
			return $plugins;
		}
	}

	return $plugins;
}
