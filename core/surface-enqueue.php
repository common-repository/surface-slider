<?php

class SurfaceSliderCommon {
	// Include CSS and JavaScript
	public static function enqueues() {	
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_style('surfaceslider', SS_PLUGIN_URL . '/assets/css/surface-style.css', array(), SS_VERSION);
		wp_enqueue_script('jquery.surfaceslider.min', SS_PLUGIN_URL . '/assets/js/jquery.surfacescript.min.js', array(), SS_VERSION, false);
	}
	
	public static function setEnqueues() {
		add_action('wp_enqueue_scripts', 'SurfaceSliderCommon::enqueues');
		add_action('admin_enqueue_scripts', 'SurfaceSliderCommon::enqueues');
	}
}
?>