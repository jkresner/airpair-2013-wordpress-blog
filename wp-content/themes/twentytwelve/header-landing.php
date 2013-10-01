<?php
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="icon" href="http://www.airpair.com/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://www.airpair.com/favicon.ico" type="image/x-icon" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/css/landing.css">
<!-- <script src="//cdn.optimizely.com/js/280598780.js"></script> -->

<?php remove_action('wp_head', 'recent_comments_style'); ?>
<?php remove_action('wp_head', 'admin-bar-css'); ?>
<?php remove_action('wp_head', 'boxes-css'); ?>
<?php remove_action('wp_head', 'wlwmanifest_link'); ?>
<?php remove_action('wp_head', 'rsd_link'); ?>
<?php remove_action('wp_enqueue_scripts', 'dd_wp_enqueue_styles'); ?>
<?php remove_action('wp_enqueue_scripts', 'twentytwelve_scripts_styles'); ?>
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>