<?php
/**
 * UNI functions and definitions
 *
 * @package UNI
 */

// Autoloader.
require_once get_template_directory() . '/vendor/autoload.php';

use Timber\{ Timber };
use DLAP\{ Managers };

$timber = new Timber();

Timber::$dirname = array( 'views', 'templates', 'dist' );

add_action(
	'init',
	function () {
		$managers = array(
			new Managers\WordPress(),
			new Managers\Customizer(),
		);

		$theme_manager = new Managers\Theme( wp_get_theme()->Name, wp_get_theme()->Version, $managers );
		$theme_manager->run();
	}
);
