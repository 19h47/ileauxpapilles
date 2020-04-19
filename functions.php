<?php
/**
 * DLAP functions and definitions
 *
 * @package dlap
 */

// Autoloader.
require_once get_template_directory() . '/vendor/autoload.php';

use Timber\{ Timber };

$timber = new Timber();

Timber::$dirname = array( 'views', 'templates', 'dist' );

DLAP\Init::run_services();

