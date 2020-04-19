<?php
/**
 * Partners
 *
 * @file        component-partners
 * @package     DLAP
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (https://19H47.fr)
 */

use Timber\{ Timber };
use DLAP\{ Transients };

$context = Timber::get_context();

$context['posts'] = Transients::partners();

Timber::render( 'components/partners.html.twig', $context );
