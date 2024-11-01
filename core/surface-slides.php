<div id="ss-slides">
	<div class="ss-slide-tabs ss-tabs ss-tabs-border">
		<ul class="ss-sortable">
			<?php
			if($edit) {
				$j = 0;
				$slides_num = count($slides);
				foreach($slides as $slide) {
					if($j == $slides_num - 1) {
						echo '<li class="ui-state-default active">';
					}
					else {
						echo '<li class="ui-state-default">';
					}
					echo '<a>' . __('Slide', 'surfaceslider') . ' <span class="ss-slide-index">' . (($slide->position) + 1) . '</span></a>';
					echo '<span class="ss-close"></span>';
					echo '</li>';
					
					$j++;
				}
			}
			?>
			<li class="ui-state-default ui-state-disabled"><a class="ss-add-new"><?php _e('Add Slide', 'surfaceslider'); ?></a></li>
		</ul>
		
		<div class="ss-slides-list">
			<?php
				if($edit) {
					foreach($slides as $slide) {
						echo '<div class="ss-slide">';
						surfaceslider_printSlide($slider, $slide, $edit);
						echo '</div>';
					}
				}
			?>
		</div>		
		<div class="ss-void-slide"><?php surfaceslider_printSlide($slider, false, $edit); ?></div>
		
		<div style="clear: both"></div>
	</div>
</div>

<?php
// Prints a slide. If the ID is not false, prints the values from MYSQL database, else prints a slide with default values. It has to receive the $edit variable because the elements.php file has to see it
function surfaceslider_printSlide($slider, $slide, $edit) {
	$void = !$slide ? true : false;	
	
	$animations = array(
		'fade' => array(__('Fade', 'surfaceslider'), true),
		'fadeLeft' => array(__('Fade left', 'surfaceslider'), false),
		'fadeRight' => array(__('Fade right', 'surfaceslider'), false),
		'slideLeft' => array(__('Slide left', 'surfaceslider'), false),
		'slideRight' => array(__('Slide right', 'surfaceslider'), false),
		'slideUp' => array(__('Slide up', 'surfaceslider'), false),
		'slideDown' => array(__('Slide down', 'surfaceslider'), false),
	);
	?>
	
	<table class="ss-slide-settings-list ss-table">
		<thead>
			<tr class="ss-table-header">
				<th><?php _e('Option', 'surfaceslider'); ?></th>
				<th><?php _e('Parameter', 'surfaceslider'); ?></th>
				<th><?php _e('Description', 'surfaceslider'); ?></th>
			</tr>
		</thead>
		
		<tbody>			
			<tr>
				<td class="ss-name"><?php _e('Background', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void): ?>
					<?php _e('Background image:', 'surfaceslider'); ?> &nbsp;
						<form>
							<input type="radio" value="0" name="ss-slide-background_type_image" checked /> <?php _e('None', 'surfaceslider'); ?> &nbsp;
							<input type="radio" value="1" name="ss-slide-background_type_image" /> <input class="ss-slide-background_type_image-upload-button ss-button ss-is-default" type="button" value="<?php _e('Select image', 'surfaceslider'); ?>" />
						</form>
						
						<br />
						<br />
						
						<?php _e('Background color:', 'surfaceslider'); ?> &nbsp;
						<form>
							<input type="radio" value="0" name="ss-slide-background_type_color" checked /> <?php _e('Transparent', 'surfaceslider'); ?> &nbsp;
							<input type="radio" value="1" name="ss-slide-background_type_color" /> <input class="ss-slide-background_type_color-picker-input ss-button ss-is-default" type="text" value="rgb(255, 255, 255)" />
						</form>
						
						<br />
						<br />
						
						<?php _e('Background position-x:', 'surfaceslider'); ?> &nbsp;
						<input type="text" value="0" class="ss-slide-background_propriety_position_x" />
						<br />
						<?php _e('Background position-y:', 'surfaceslider'); ?> &nbsp;
						<input type="text" value="0" class="ss-slide-background_propriety_position_y" />
						
						<br />
						<br />
						
						<?php _e('Background repeat:', 'surfaceslider'); ?> &nbsp;
						<form>
							<input type="radio" value="1" name="ss-slide-background_repeat" checked /> <?php _e('Repeat', 'surfaceslider'); ?> &nbsp;
							<input type="radio" value="0" name="ss-slide-background_repeat" /> <?php _e('No repeat', 'surfaceslider'); ?>
						</form>
						
						<br />
						<br />
						
						<?php _e('Background size:', 'surfaceslider'); ?> &nbsp;
						<input type="text" value="auto" class="ss-slide-background_propriety_size" />
					<?php else: ?>
						<?php _e('Background image:', 'surfaceslider'); ?> &nbsp;
						<form>
							<?php if($slide->background_type_image == 'none' || $slide->background_type_image == 'undefined'): ?>
								<input type="radio" value="0" name="ss-slide-background_type_image" checked /> <?php _e('None', 'surfaceslider'); ?> &nbsp;
								<input type="radio" value="1" name="ss-slide-background_type_image" /> <input class="ss-slide-background_type_image-upload-button ss-button ss-is-default" type="button" value="<?php _e('Select image', 'surfaceslider'); ?>" />
							<?php else: ?>
								<input type="radio" value="0" name="ss-slide-background_type_image" /> <?php _e('None', 'surfaceslider'); ?> &nbsp;
								<input type="radio" value="1" name="ss-slide-background_type_image" checked /> <input class="ss-slide-background_type_image-upload-button ss-button ss-is-default" type="button" value="<?php _e('Select image', 'surfaceslider'); ?>" />
							<?php endif; ?>
						</form>	
						
						<br />
						<br />
						
						<?php _e('Background color:', 'surfaceslider'); ?> &nbsp;
						<form>
							<?php if($slide->background_type_color == 'transparent'): ?>
								<input type="radio" value="0" name="ss-slide-background_type_color" checked /> <?php _e('Transparent', 'surfaceslider'); ?> &nbsp;
								<input type="radio" value="1" name="ss-slide-background_type_color" /> <input class="ss-slide-background_type_color-picker-input ss-button ss-is-default" type="text" value="rgb(255, 255, 255)" />
							<?php else: ?>
								<input type="radio" value="0" name="ss-slide-background_type_color" /> <?php _e('Transparent', 'surfaceslider'); ?> &nbsp;
								<input type="radio" value="1" name="ss-slide-background_type_color" checked /> <input class="ss-slide-background_type_color-picker-input ss-button ss-is-default" type="text" value="<?php echo $slide->background_type_color; ?>" />
							<?php endif; ?>	
						</form>
						
						<br />
						<br />
						
						<?php _e('Background position-x:', 'surfaceslider'); ?> &nbsp;
						<input type="text" value="<?php echo $slide->background_propriety_position_x; ?>" class="ss-slide-background_propriety_position_x" />
						<br />
						<?php _e('Background position-y:', 'surfaceslider'); ?> &nbsp;
						<input type="text" value="<?php echo $slide->background_propriety_position_y; ?>" class="ss-slide-background_propriety_position_y" />
						
						<br />
						<br />
						
						<?php _e('Background repeat:', 'surfaceslider'); ?> &nbsp;
						<form>
							<?php if($slide->background_repeat == 'repeat'): ?>
								<input type="radio" value="1" name="ss-slide-background_repeat" checked /> <?php _e('Repeat', 'surfaceslider'); ?> &nbsp;
								<input type="radio" value="0" name="ss-slide-background_repeat" /> <?php _e('No repeat', 'surfaceslider'); ?>
							<?php else: ?>
								<input type="radio" value="1" name="ss-slide-background_repeat" /> <?php _e('Repeat', 'surfaceslider'); ?> &nbsp;
								<input type="radio" value="0" name="ss-slide-background_repeat" checked /> <?php _e('No repeat', 'surfaceslider'); ?>
							<?php endif; ?>
						</form>
						
						<br />
						<br />
						
						<?php _e('Background size:', 'surfaceslider'); ?> &nbsp;
						<input type="text" value="<?php echo $slide->background_propriety_size; ?>" class="ss-slide-background_propriety_size" />
					<?php endif; ?>
				</td>
				<td class="ss-description">
					<?php _e('The background of the slide and its proprieties.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('In animation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select class="ss-slide-data_in">
						<?php
						foreach($animations as $key => $value) {
							echo '<option value="' . $key . '"';
							if(($void && $value[1]) || (!$void && $slide->data_in == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('The in animation of the slide.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Out animation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select class="ss-slide-data_out">
						<?php
						foreach($animations as $key => $value) {
							echo '<option value="' . $key . '"';
							if(($void && $value[1]) || (!$void && $slide->data_in == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('The out animation of the slide.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Time', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-slide-data_time" type="text" value="3000" />';
					else echo '<input class="ss-slide-data_time" type="text" value="' . $slide->data_time .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('The time that the slide will remain on the screen.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Ease In', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-slide-data_easeIn" type="text" value="300" />';
					else echo '<input class="ss-slide-data_easeIn" type="text" value="' . $slide->data_easeIn .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('The time that the slide will take to get in.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Ease Out', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-slide-data_easeOut" type="text" value="300" />';
					else echo '<input class="ss-slide-data_easeOut" type="text" value="' . $slide->data_easeOut .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('The time that the slide will take to get out.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Custom CSS', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<textarea class="ss-slide-custom_css"></textarea>';
					else echo '<textarea class="ss-slide-custom_css">' . stripslashes($slide->custom_css) . '</textarea>';
					?>
				</td>
				<td class="ss-description">
					<?php _e('Apply CSS to the slide.', 'surfaceslider'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	
	<br />
	<br />
	
	<?php
	// If the slide is not void, select her elements
	if(!$void) {
		global $wpdb;
		$id = isset($_GET['id']) ? $_GET['id'] : NULL;
		$slide_parent = $slide->position;
		$elements = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'surfaceslider_elements WHERE slider_parent = ' . $id . ' AND slide_parent = ' . $slide_parent);
	}
	else {
		$slide_id = NULL;
		$elements = NULL;
	}
	
	surfaceslider_printElements($edit, $slider, $slide, $elements);
}
?>