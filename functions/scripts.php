<?php
function phototheme_enqueue_scripts()	{
	wp_deregister_script('jquery');
	
	wp_register_script('jquery',get_bloginfo('template_url').'/js/jquery-1.4.2.min.js',array(),'1.4.2');
	wp_register_script('jquery.ba-hashchange.min',get_bloginfo('template_url').'/js/jquery.ba-hashchange.min.js',array(),'1.2');
	wp_register_script('phototheme.background',get_bloginfo('template_url').'/js/siteSetup.js',array('jquery','jquery.ba-hashchange.min'));
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery.ba-hashchange.min');
	wp_enqueue_script('phototheme.background');
}

function phototheme_admin_jquery()	{
	define('CONCATENATE_SCRIPTS',FALSE);
	wp_deregister_script('jquery');
	
	wp_register_script('jquery',get_bloginfo('template_url').'/js/jquery-1.4.2.min.js',array(),'1.4.2');
	
	wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
}

add_action('get_header','phototheme_enqueue_scripts',0);
add_action('init','phototheme_admin_jquery',0);
