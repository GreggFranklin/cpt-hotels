<?php
/*
Plugin Name:CPT Hotels
Description:Adds a Hotel post type to your theme
Author:Gregg Franklin
Author URI:http://www.greggfranklin.com
Version:1.0
License:GNU General Public License version 3.0
License URI:http://www.gnu.org/licenses/gpl-3.0.html
*/

/*
 * Require Additional Plugins
 * http://tgmpluginactivation.com/
 */
require_once ( dirname(__FILE__) .'/recommend-plugins.php' );

/*
 * Build Custom Post Type
 * http://generatewp.com/post-type/
 */
require_once( dirname(__FILE__) . '/register_post_type.php' );

/*
 * Add metaboxes using Advance Custom Fields 
 * http://www.advancedcustomfields.com/resources/getting-started/including-acf-in-a-plugin-theme/
 */
define( 'ACF_LITE' , true ); //Hide ACF admin menu
include_once('advanced-custom-fields/acf.php' ); // Files from plugin
require_once( dirname(__FILE__) . '/metaboxes.php' ); // Export from plugin

/*
 * Add image sizes
 * http://codex.wordpress.org/Function_Reference/add_image_size
 */
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'logo', 90, 90, true ); // (cropped)
	add_image_size( 'hotel_feature', 325, 9999 ); // (unlimited height)
}

/* Enqueue Scripts
 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 *
 * Javascripts
 */
function custom_js_script() {
	wp_enqueue_script('cycle', plugins_url('/inc/js/cycle.js', __FILE__), array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script', plugins_url('/inc/js/custom.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('template_redirect', 'custom_js_script');

/* 
 * Add CSS
 */
function custom_css_script() {
	wp_enqueue_style('style', plugins_url('/inc/css/style.css', __FILE__));
}
add_action('template_redirect', 'custom_css_script');

// Template pages
require_once( dirname(__FILE__) . '/template_pages.php' );