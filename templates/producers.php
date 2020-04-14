<?php
/**
 * Template Name: Producers
 *
 * @package DLAP
 */

use Timber\{ Timber, Post };

$context = Timber::context();

$context['post']         = new Post();
$context['namespace']    = 'page';
$context['body_classes'] = array( 'Producers-page', 'background-color-cyan-dark-moderate', 'color-gray-very-light' );

$templates = array( 'index.html.twig' );

Timber::render( $templates, $context );
