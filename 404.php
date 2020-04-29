<?php
/**
 * 404
 *
 * @package DLAP
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @since 0.0.0
 * @version 0.0.0
 */

use Timber\{ Timber, Post };

$context = Timber::context();

$context['post']         = new Post();
$context['namespace']    = 'page';
$context['body_classes'] = array( 'background-color-cyan-very-dark-desaturated', 'color-gray-very-light' );

$context['post']->title = '404';

$templates = array( 'index.html.twig' );

Timber::render( $templates, $context );
