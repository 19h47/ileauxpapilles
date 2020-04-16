<?php
/**
 * References
 *
 * @file        component-references
 * @package     DLAP
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (https://19H47.fr)
 */

use Timber\{ Timber };
use DLAP\{ Transients };

$context = Timber::get_context();

$context['posts'] = Transients::references();

Timber::render( 'components/references.html.twig', $context );
