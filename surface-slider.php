<?php
/**
 * Plugin Name: Surface Slider
 * Description: Surface Slider Plugin is a richly featured, upgraded slider plugin that makes it very easy to create multiple sliders with number of slides on each slider. What's more, Surface Slider Plugin also sports fantastic animation effects that enhances the creation of multiple elements on each slides. It smoothly integrates with all wordpress sites to provide you an enriching experience in creating your own sliders which suit best for your project.
 * Version: 1.0.1
 * Author: SketchThemes
 * Author URI: http://sketchthemes.com
 * License: MIT
 */

/*************/
/** GLOBALS **/
/*************/ 

define('SS_VERSION', '1.0.0');
define('SS_PATH', plugin_dir_path(__FILE__));
define('SS_PLUGIN_URL', plugins_url() . '/surface-slider');

require_once SS_PATH . 'core/surface-enqueue.php';
require_once SS_PATH . 'core/surface-db.php';
require_once SS_PATH . 'core/surface-front.php';

// Create (or remove) 3 tables: the sliders settings, the slides settings and the elements proprieties. We will also store the current version of the plugin			
register_activation_hook(__FILE__, array('SurfaceSliderTables', 'setVersion'));			
register_activation_hook(__FILE__, array('SurfaceSliderTables', 'setTables'));
register_uninstall_hook(__FILE__, array('SurfaceSliderTables', 'removeVersion'));
register_uninstall_hook(__FILE__, array('SurfaceSliderTables', 'dropTables'));


// Loads language file
function surface_textDomain() { 
	load_plugin_textdomain('surfaceslider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
add_action('plugins_loaded', 'surface_textDomain');


// This is a variable that should be included first to prevent backend issues.
if(is_admin()) {
	require_once SS_PATH . 'core/surface-main.php';
	SurfaceSliderAdmin::setIsAdminJs();
}

// CSS and Javascript
SurfaceSliderCommon::setEnqueues();

SurfaceSliderFrontend::addShortcode();

if(is_admin()) {
	// Tables
	if(SS_VERSION != get_option('ss_version')) {
		SurfaceSliderTables::setVersion();
		SurfaceSliderTables::setTables();
	}
	
	SurfaceSliderAdmin::setEnqueues();
	SurfaceSliderAdmin::showSettings();
	
	// Ajax functions
	require_once SS_PATH . 'core/surface-ajax-function.php';	
}

?>