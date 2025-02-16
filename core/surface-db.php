<?php

class SurfaceSliderTables {

	// Update the current Surface Slider version in the database
	public static function setVersion() {
		update_option('ss_version', SS_VERSION);
	}

	public static function removeVersion() {
		delete_option('ss_version');
	}

	// Creates or updates all the tables
	public static function setTables() {
		self::setSlidersTable();
		self::setSlidesTable();
		self::setElementsTable();
	}

	public static function setSlidersTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'surfaceslider_sliders';
		
		$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name TEXT CHARACTER SET utf8,
		alias TEXT CHARACTER SET utf8,
		layout TEXT CHARACTER SET utf8,
		responsive INT,
		startWidth INT,
		startHeight INT,
		automaticSlide INT,
		showControls INT,
		showNavigation INT,
		enableSwipe INT DEFAULT 1,
		showProgressBar INT,
		pauseOnHover INT,
		callbacks TEXT CHARACTER SET utf8,
		UNIQUE KEY id (id)
		);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	// Warning: the time variable is a string because it could contain the 'all' word
	public static function setSlidesTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'surfaceslider_slides';
		
		$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		slider_parent mediumint(9),
		position INT,
		background_type_image TEXT CHARACTER SET utf8,
		background_type_color TEXT CHARACTER SET utf8,
		background_propriety_position_x TEXT CHARACTER SET utf8,
		background_propriety_position_y TEXT CHARACTER SET utf8,
		background_repeat TEXT CHARACTER SET utf8,
		background_propriety_size TEXT CHARACTER SET utf8,
		data_in TEXT CHARACTER SET utf8,
		data_out TEXT CHARACTER SET utf8,
		data_time INT,
		data_easeIn INT,
		data_easeOut INT,
		custom_css TEXT CHARACTER SET utf8,
		UNIQUE KEY id (id)
		);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public static function setElementsTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'surfaceslider_elements';
		
		$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		slider_parent mediumint(9),
		slide_parent mediumint(9),
		position INT,
		type TEXT CHARACTER SET utf8,
		data_easeIn INT,
		data_easeOut INT,
		data_ignoreEaseOut INT DEFAULT 0,
		data_delay INT,
		data_time TEXT CHARACTER SET utf8,
		data_top FLOAT,
		data_left FLOAT,
		z_index INT,
		data_in TEXT CHARACTER SET utf8,
		data_out TEXT CHARACTER SET utf8,
		custom_css TEXT CHARACTER SET utf8,
		inner_html TEXT CHARACTER SET utf8,
		image_src TEXT CHARACTER SET utf8,
		image_alt TEXT CHARACTER SET utf8,
		link TEXT CHARACTER SET utf8 DEFAULT '',
		link_new_tab INT DEFAULT 0,
		UNIQUE KEY id (id)
		);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	// Drops all the slider tables
	public static function dropTables() {
		global $wpdb;
		
		self::dropTable($wpdb->prefix . 'surfaceslider_sliders');
		self::dropTable($wpdb->prefix . 'surfaceslider_slides');
		self::dropTable($wpdb->prefix . 'surfaceslider_elements');
	}

	public static function dropTable($table_name) {
		global $wpdb;
		
		$sql = 'DROP TABLE ' . $table_name . ';';
		$wpdb->query($sql);
	}

}

?>