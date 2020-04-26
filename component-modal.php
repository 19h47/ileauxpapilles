<?php
/**
 * Modal
 *
 * @file        component-modal
 * @package     DLAP
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (https://19H47.fr)
 */

use Timber\{ Timber };

$context = Timber::get_context();

$context['pop_up'] = Timber::get_widgets( 'modal' );

Timber::render( 'components/modal.html.twig', $context );
