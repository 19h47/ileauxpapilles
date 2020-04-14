<?php
/**
 * Template Name: Restaurant
 *
 * @package DLAP
 */

use Timber\{ Timber, Post };

$context = Timber::context();

$context['post']         = new Post();
$context['namespace']    = 'page';
$context['body_classes'] = array( 'Restaurant-page', 'background-color-cyan-very-dark-desaturated', 'color-gray-very-light' );

$templates = array( 'index.html.twig' );

Timber::render( $templates, $context );
