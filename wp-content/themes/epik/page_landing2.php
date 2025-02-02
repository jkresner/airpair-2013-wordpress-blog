<?php

// This file adds the Landing template to the Epik Theme.

// Template Name: Landing2

// Add custom body class to the head
add_filter( 'body_class', 'epik_add_body_class' );
function epik_add_body_class( $classes ) {
   $classes[] = 'epik-landing';
   return $classes;
}

add_action( 'genesis_pre_framework', 'custom_content' );
function custom_content() { 
	echo '<h1>hell yeah</h1>';
}

// Remove header, navigation, breadcrumbs, footer widgets, footer 
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_before_header', 'genesis_do_subnav' );
remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

genesis();