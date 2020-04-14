<?php
/**
 * Template Name: Voucher
 *
 * @package DLAP
 */

use Timber\{ Timber, Post };

$context = Timber::context();

$context['post']         = new Post();
$context['namespace']    = 'page';
$context['node_type']    = 'VoucherPage';
$context['body_classes'] = array( 'background-color-cyan-very-dark-desaturated', 'color-gray-very-light' );

$templates = array( 'pages/voucher.html.twig' );

Timber::render( $templates, $context );
