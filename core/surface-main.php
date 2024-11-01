<?php

class SurfaceSliderAdmin {
	
	// Creates the menu and the admin panel
	public static function showSettings() {
		add_action('admin_menu', 'SurfaceSliderAdmin::pluginMenus');
		add_action( 'admin_init', 'SurfaceSliderAdmin::surface_process_settings_export' );
		add_action( 'admin_init', 'SurfaceSliderAdmin::surface_process_settings_import' );
	}
	
	public static function pluginMenus() {
		add_menu_page(__('Surface Slider', 'surfaceslider'), __('Surface Slider', 'surfaceslider'), 'manage_options', 'surfaceslider', 'SurfaceSliderAdmin::displayPage');
		add_submenu_page('surfaceslider', __('Import Export', 'surfaceslider'), __('Import Export', 'surfaceslider'), 'manage_options', 'surface_slider', 'SurfaceSliderAdmin::surface_settings_page');
	}
	
	// Go to the correct page
	public static function displayPage() {
		if(!isset($_GET['view'])) {
			$index = 'home';
		}
		else {
			$index = $_GET['view'];
		}
		
		global $wpdb;
		
		// Check what the user is doing: is it adding or modifying a slider? 
		if(isset($_GET['view']) && $_GET['view'] == 'add') {
			$edit = false;
			$id = NULL;
		}
		else {
			$edit = true;
			$id = isset($_GET['id']) ? $_GET['id'] : NULL;
			if(isset($id))
				$slider = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'surfaceslider_sliders WHERE id = ' . $id);
		}
		
		?>
		<div
		class="wrap ss-admin"
		<?php if($edit && isset($slider)): ?>
			style="width: <?php echo $slider->startWidth; ?>px;"
		<?php else: ?>
			style="width: 1170px;"
		<?php endif; ?>
		>	
		<div class="ss-overlay"></div>
		
			<noscript class="ss-no-js">
				<div class="ss-message ss-message-error" style="display: block;"><?php _e('JavaScript must be enabled to view this page correctly.', 'surfaceslider'); ?></div>
			</noscript>
			
			<div class="ss-message ss-message-ok" style="display: none;"><?php _e('Operation completed successfully.', 'surfaceslider'); ?></div>
			<div class="ss-message ss-message-error" style="display: none;"><?php _e('Something went wrong.', 'surfaceslider'); ?></div>
			<?php if(! $edit): ?>
				<div class="ss-message ss-message-warning"><?php _e('When you\'ll click "Save Settings", you\'ll be able to add slides and elements.', 'surfaceslider'); ?></div>
			<?php endif; ?>
			
			<!-- <h2 class="ss-logo" title="Surface Slider"> -->
				<a class="ss-slider-logo" href="?page=surfaceslider">
					<img src="<?php echo SS_PLUGIN_URL . '/core/images/surface-logo.jpg' ?>" alt="Surface Slider" />
				</a>
			<!-- </h2> -->
			
			<br />
			<br />
			
			<?php
			
			switch($index) {
				case 'home':
					self::displayHome();
				break;
				
				case 'add':
				case 'edit':
					self::displaySlider();
				break;
			}
			
			?>
		
		</div>
		<?php
	}
	
	// Displays the main plugin page
	public static function displayHome() {		
		?>
		<div class="ss-home">
			<?php require_once SS_PATH . 'core/surface-backend.php'; ?>
		</div>
		<?php
	}
	
	// Displays the slider page in wich you can add or modify sliders, slides and elements
	public static function displaySlider() {
		global $wpdb;
		
		// Check what the user is doing: is it adding or modifying a slider? 
		if($_GET['view'] == 'add') {
			$edit = false;
			$id = NULL;	//This variable will be used in other files. It contains the ID of the SLIDER that the user is editing
		}
		else {
			$edit = true;
			$id = isset($_GET['id']) ? $_GET['id'] : NULL;
			$slider = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'surfaceslider_sliders WHERE id = ' . $id);
			$slides = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'surfaceslider_slides WHERE slider_parent = ' . $id . ' ORDER BY position');
			// The elements variable are updated in the foreachh() loop directly in the "slides.php" file
		}
		?>
		
		<div class="ss-slider <?php echo $edit ? 'ss-edit-slider' : 'ss-add-slider' ?>">
			<div class="ss-tabs ss-tabs-fade ss-tabs-switch-interface">
				<?php if($edit): ?>
					<ul>
					
						<li>
							<a href="#ss-slider-settings" class="ss-btn"><?php _e('Slider Settings', 'surfaceslider'); ?></a>
						</li>
						<li>
							<a href="#ss-slides" class="ss-btn"><?php _e('Edit Slides', 'surfaceslider'); ?></a>
						</li>
					</ul>
					
					<br />
					<br />
					<br />					
				<?php endif; ?>
				
				<?php require_once SS_PATH . 'core/surface-sliders.php'; ?>
				<?php 
				if($edit) {
					require_once SS_PATH . 'core/surface-elements.php';
					require_once SS_PATH . 'core/surface-slides.php';
				}
				?>
			</div>
			
			<br />
			<div class="ss-savebutton">
			<a class="ss-button ss-is-primary ss-save-settings" data-id="<?php echo $id; ?>" href="#"><?php _e('Save Settings', 'surfaceslider'); ?></a>
			</div>
		</div>
		
		<?php
	}
	
	// Avoid incompatibility issues
	public static function isAdminJs() {	
		?>
		<script type="text/javascript">
			var surfaceslider_is_wordpress_admin = true;
		</script>
		<?php
	}
	
	public static function setIsAdminJs() {
		add_action('admin_enqueue_scripts', 'SurfaceSliderAdmin::isAdminJs');
	}
	
	// Include CSS and JavaScript
	public static function enqueues() {	
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_media();
		
		wp_register_script('surfaceslider-admin', SS_PLUGIN_URL . '/core/js/surface-admin-script.js', array('wp-color-picker'), SS_VERSION, true);
		
		self::localization();
		
		wp_enqueue_style('surfaceslider-admin', SS_PLUGIN_URL . '/core/css/surface-admin-style.css', array(), SS_VERSION);
		wp_enqueue_script('surfaceslider-admin');
		
		$wp_version = get_bloginfo('version');
		$menu_icon_url = SS_PLUGIN_URL . '/core/images/menu-icon.png';
		if($wp_version < 3.8) {
			?>
			<style type="text/css">
				#adminmenu .toplevel_page_surfaceslider div.wp-menu-image {
					background-image: url('<?php echo $menu_icon_url; ?>');
					background-repeat: no-repeat;
					background-position: -20px center;
				}

				#adminmenu .toplevel_page_surfaceslider:hover div.wp-menu-image {
					background-position: -20px center;
				}

				#adminmenu .toplevel_page_surfaceslider.current div.wp-menu-image {
					background-position: 8px center;
				}

				#adminmenu .current.toplevel_page_surfaceslider:hover div.wp-menu-image {
					background-position: 8px center;
				}
			</style>
			<?php
		}
		else {
			?>
			<style type="text/css">
				#adminmenu .toplevel_page_surfaceslider div.wp-menu-image {
					background-image: url('<?php echo $menu_icon_url; ?>');
					background-repeat: no-repeat;
					background-position: 8px center;
					opacity: .6;
					filter: alpha(opacity=60);
				}

				#adminmenu .toplevel_page_surfaceslider:hover div.wp-menu-image {
					background-position: -20px center;
					opacity: 1;
					filter: alpha(opacity=100);
				}

				#adminmenu .toplevel_page_surfaceslider.current div.wp-menu-image {
					opacity: 1;
					filter: alpha(opacity=100);
				}

				#adminmenu .current.toplevel_page_surfaceslider:hover div.wp-menu-image {
					background-position: 8px center;
					opacity: 1;
					filter: alpha(opacity=100);
				}
			</style>
			<?php
		}
	}
			
	public static function setEnqueues() {
		add_action('admin_enqueue_scripts', 'SurfaceSliderAdmin::enqueues');
	}
	
	public static function localization() {
		// Here the translations for the admin.js file
		$surfaceslider_translations = array(
			'slide' => __('Slide', 'surfaceslider'),
			'slide_delete_confirm' => __('The slide will be deleted. Are you sure?', 'surfaceslider'),
			'slide_delete_just_one' => __('You can\'t delete this. You must have at least one slide.', 'surfaceslider'),
			'slider_delete_confirm' => __('The slider will be deleted. Are you sure?', 'surfaceslider'),
			'text_element_default_html' => __('Text element', 'surfaceslider'),
			'slide_live_preview' => __('Live preview', 'surfaceslider'),
			'slide_stop_preview' => __('Stop preview', 'surfaceslider'),
		);
		wp_localize_script('surfaceslider-admin', 'surfaceslider_translations', $surfaceslider_translations);
	}

	/**
	 * Render the settings page
	 */
	public static function surface_settings_page() {

	?>
		<div class="wrap">
			
			<div class="metabox-holder">
				<div class="postbox">
					<h3><span><?php _e( 'Export Surface Slider', 'surfaceslider' ); ?></span></h3>
					<div class="inside">
						<p><?php _e( 'Export the slider settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'surfaceslider' ); ?></p>
						<form method="post">
							<p><input type="hidden" name="surface_action" value="export_settings" /></p>
							<p><?php 
								global $wpdb;
								$table_name = $wpdb->prefix . 'surfaceslider_sliders';
								$sql_alias = 'SELECT alias FROM '.$table_name.';';
								$resultsql_alias = $wpdb->get_results( $sql_alias );
							 ?>
								<select id="aliass" name="import_alias">
								<?php 
								foreach ($resultsql_alias as $key) {	
									echo '<option>'.$key->alias.'</option>';
								}
								?>
								</select>
							</p>
							<p>
								<?php wp_nonce_field( 'surface_export_nonce', 'surface_export_nonce' ); ?>
								<?php submit_button( __( 'Export', 'surfaceslider' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->

				<div class="postbox">
					<h3><span><?php _e( 'Import Surface Slider', 'surfaceslider' ); ?></span></h3>
					<div class="inside">
						<p><?php _e( 'Import the slider settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'surfaceslider' ); ?></p>
						<form method="post" enctype="multipart/form-data">
							<p>
								<input type="file" name="import_file"/>
							</p>
							<p>
								<input type="hidden" name="surface_action" value="import_settings" />
								<?php wp_nonce_field( 'surface_import_nonce', 'surface_import_nonce' ); ?>
								<?php submit_button( __( 'Import', 'surfaceslider' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->
			</div><!-- .metabox-holder -->

		</div><!--end .wrap-->
		<?php
	}

	/**
	 * Process a settings export that generates a .json file of the shop settings
	 */
	public static function surface_process_settings_export() {

		if( empty( $_POST['surface_action'] ) || 'export_settings' != $_POST['surface_action'] )
			return;

		if( ! wp_verify_nonce( $_POST['surface_export_nonce'], 'surface_export_nonce' ) )
			return;

		if( ! current_user_can( 'manage_options' ) )
			return;

		$get_alias = isset($_POST['import_alias']) ? $_POST['import_alias'] : false;

		if ( $get_alias ) {

			global $wpdb;
			$table_name = $wpdb->prefix . 'surfaceslider_sliders';
			$table_name2 = $wpdb->prefix . 'surfaceslider_slides';
			$table_name3 = $wpdb->prefix . 'surfaceslider_elements';
			$sql_slider = 'SELECT * FROM '.$table_name.' WHERE alias = "'. $get_alias .'";';
			$resultsql_slider = $wpdb->get_results( $sql_slider );
			foreach ($resultsql_slider as $key) {
				$slider_id = $key->id;
			}
			$sql_slides = 'SELECT * FROM '.$table_name2.' WHERE slider_parent = "'. $slider_id .'";';

			$sql_elements = 'SELECT * FROM '.$table_name3.' WHERE slider_parent = "'. $slider_id .'";';

			$resultsql_slides = $wpdb->get_results( $sql_slides );
			$resultsql_elements = $wpdb->get_results( $sql_elements );

			ignore_user_abort( true );

			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=surface-settings-export-' . date( 'm-d-Y' ) . '.json' );
			header( "Expires: 0" );

			$flag = false;
			echo "[";
			foreach ( $resultsql_slider as $slider ) 
			{

				if( $flag ){ echo ","; }
				$flag = true;

				echo '{"id":"'.$slider->id
					.'","name":"'.$slider->name
					.'","alias":"'.$slider->alias
					.'","layout":"'.$slider->layout
					.'","responsive":"'.$slider->responsive
					.'","startWidth":"'.$slider->startWidth
					.'","startHeight":"'.$slider->startHeight
					.'","automaticSlide":"'.$slider->automaticSlide
					.'","showControls":"'.$slider->showControls
					.'","showNavigation":"'.$slider->showNavigation
					.'","enableSwipe":"'.$slider->enableSwipe
					.'","showProgressBar":"'.$slider->showProgressBar
					.'","pauseOnHover":"'.$slider->pauseOnHover.'"}';
			}
			echo "]***";


			$flag = false;
			echo "[";
			foreach ( $resultsql_slides as $slide ) 
			{
				if( $flag ){ echo ","; }
				$flag = true;

				echo '{"id":"'.$slide->id
					.'","slider_parent":"'.$slide->slider_parent
					.'","position":"'.$slide->position
					.'","background_type_image":"'.$slide->background_type_image
					.'","background_type_color":"'.$slide->background_type_color
					.'","background_propriety_position_x":"'.$slide->background_propriety_position_x
					.'","background_propriety_position_y":"'.$slide->background_propriety_position_y
					.'","background_repeat":"'.$slide->background_repeat
					.'","background_propriety_size":"'.$slide->background_propriety_size
					.'","data_in":"'.$slide->data_in
					.'","data_out":"'.$slide->data_out
					.'","data_time":"'.$slide->data_time
					.'","data_easeIn":"'.$slide->data_easeIn
					.'","data_easeOut":"'.$slide->data_easeOut
					.'","custom_css":"'.$slide->custom_css.'"}';
			}
			echo "]***";
			
			$flag = false;
			echo "[";
			foreach ( $resultsql_elements as $element ) 
			{
				if( $flag ){ echo ","; }
				$flag = true;

				echo '{"id":"'.$element->id
					.'","slider_parent":"'.$element->slider_parent
					.'","slide_parent":"'.$element->slide_parent
					.'","position":"'.$element->position
					.'","type":"'.$element->type
					.'","data_easeIn":"'.$element->data_easeIn
					.'","data_easeOut":"'.$element->data_easeOut
					.'","data_ignoreEaseOut":"'.$element->data_ignoreEaseOut
					.'","data_delay":"'.$element->data_delay
					.'","data_time":"'.$element->data_time
					.'","data_top":"'.$element->data_top
					.'","data_left":"'.$element->data_left
					.'","z_index":"'.$element->z_index
					.'","data_in":"'.$element->data_in
					.'","data_out":"'.$element->data_out
					.'","custom_css":"'.$element->custom_css
					.'","inner_html":"'.$element->inner_html
					.'","image_src":"'.$element->image_src
					.'","image_alt":"'.$element->image_alt
					.'","link":"'.$element->link
					.'","link_new_tab":"'.$element->link_new_tab.'"}';
			}
			echo "]";
		}
		exit;
	}
	

	/**
	 * Process a settings import from a json file
	 */
	public static function surface_process_settings_import() {

		if( empty( $_POST['surface_action'] ) || 'import_settings' != $_POST['surface_action'] )
			return;

		if( ! wp_verify_nonce( $_POST['surface_import_nonce'], 'surface_import_nonce' ) )
			return;

		if( ! current_user_can( 'manage_options' ) )
			return;

		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );

		if( $extension != 'json' ) {
			wp_die( __( 'Please upload a valid .json file' ) );
		}

		$import_file = $_FILES['import_file']['tmp_name'];

		if( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import' ) );
		}

		// Retrieve the settings from the file and convert the json object to an array.
		$settings = file_get_contents( $import_file );


		$settings = explode("***", $settings);

		$json = json_decode($settings[0]);
		$json2 = json_decode($settings[1]);
		$json3 = json_decode($settings[2]);
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'surfaceslider_sliders';
		$table_name2 = $wpdb->prefix . 'surfaceslider_slides';
		$table_name3 = $wpdb->prefix . 'surfaceslider_elements';
		$output = true;

		foreach ($json as $val) {

			$check_alias = $wpdb->query( "SELECT alias FROM ".$table_name." WHERE alias='".$val->alias."';" );

			if( $check_alias != 0 ) {


				$output = $wpdb->insert(
					$table_name,
					array(
						'name' => $val->name,
						'alias' => $val->alias.'1',
						'layout' => $val->layout,
						'responsive' => $val->responsive,
						'startWidth' => $val->startWidth,
						'startHeight' => $val->startHeight,
						'automaticSlide' => $val->automaticSlide,
						'showControls' => $val->showControls,
						'showNavigation' => $val->showNavigation,
						'showProgressBar' => $val->showProgressBar,
						'pauseOnHover' => $val->pauseOnHover,
						'callbacks' => '',
						'enableSwipe' => $val->enableSwipe,
					),
					array(
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%d',
					)
				);
				
			} else {

				// Returning
				$output = $wpdb->insert(
					$table_name,
					array(
						'name' => $val->name,
						'alias' => $val->alias,
						'layout' => $val->layout,
						'responsive' => $val->responsive,
						'startWidth' => $val->startWidth,
						'startHeight' => $val->startHeight,
						'automaticSlide' => $val->automaticSlide,
						'showControls' => $val->showControls,
						'showNavigation' => $val->showNavigation,
						'showProgressBar' => $val->showProgressBar,
						'pauseOnHover' => $val->pauseOnHover,
						'callbacks' => '',
						'enableSwipe' => $val->enableSwipe,
					),
					array(
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%d',
					)
				);

			}
			$output = $wpdb->insert_id;
				
		}

		foreach ($json2 as $key2) {

			$output2 = $wpdb->insert(
					$table_name2,
					array(
						'slider_parent' => $output,
						'position' => $key2->position,
						'background_type_image' => $key2->background_type_image,
						'background_type_color' => $key2->background_type_color,
						'background_propriety_position_x' => $key2->background_propriety_position_x,
						'background_propriety_position_y' => $key2->background_propriety_position_y,
						'background_repeat' => $key2->background_repeat,
						'background_propriety_size' => $key2->background_propriety_size,
						'data_in' => $key2->data_in,
						'data_out' => $key2->data_out,
						'data_time' => $key2->data_time,
						'data_easeIn' => $key2->data_easeIn,
						'data_easeOut' => $key2->data_easeOut,
						'custom_css' => $key2->custom_css,
					),
					array(
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
					)
				);
		}

		foreach ($json3 as $key3) {
			$output3 = $wpdb->insert(
						$table_name3,
						array(	
							'slider_parent' => $output,
							'slide_parent' => $key3->slide_parent,
							'position' => $key3->position,
							'type' => $key3->type,
							'inner_html' => $key3->inner_html,
							'image_src' => $key3->image_src,
							'image_alt' => $key3->image_alt,
							'data_left' => $key3->data_left,
							'data_top' => $key3->data_top,
							'z_index' => $key3->z_index,
							'data_delay' => $key3->data_delay,
							'data_time' => $key3->data_time,
							'data_in' => $key3->data_in,
							'data_out' => $key3->data_out,
							'data_easeIn' => $key3->data_easeIn,
							'data_easeOut' => $key3->data_easeOut,
							'custom_css' => $key3->custom_css,
							'link' => $key3->link,
							'link_new_tab' => $key3->link_new_tab,
							'data_ignoreEaseOut' => $key3->data_ignoreEaseOut
						),
						array(
							'%d',
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%s',
							'%s',
							'%d',
							'%d',
						)
					);
		}

		wp_safe_redirect( admin_url( 'admin.php?page=surface_slider' ) );
		exit;

	}
	
}

?>