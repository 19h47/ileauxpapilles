<?php
/**
 * Template Name: Gift Coupon
 *
 * @package DLAP
 */

use Timber\{ Timber, Post };

$context = Timber::context();

$context['post']         = new Post();
$context['namespace']    = 'page';
$context['node_type']    = 'GiftCouponPage';
$context['body_classes'] = array( 'Gift-coupon-page', 'background-color-cyan-very-dark-desaturated', 'color-gray-very-light' );

$templates = array( 'pages/gift-coupon.html.twig' );

Timber::render( $templates, $context );
