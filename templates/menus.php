<?php
/**
 * Template Name: Menus
 *
 * @package DLAP
 */

use Timber\{ Timber, Post };

$context = Timber::context();

$context['post']         = new Post();
$context['namespace']    = 'page';
$context['body_classes'] = array( 'Menus-page', 'background-color-gray-very-dark', 'color-gray-very-light' );

$templates = array( 'index.html.twig' );

Timber::render( $templates, $context );
