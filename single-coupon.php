<?php
/**
 * Single: Coupon
 *
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
$context['body_classes'] = array( 'Single-coupon' );

$templates = array( 'pages/single-coupon.html.twig' );

Timber::render( $templates, $context );
