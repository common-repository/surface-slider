<div id="ss-slider-settings">
	<?php
	// Contains the key, the display name and a boolean: true if is the default option
	$slider_select_options = array(
		'layout' => array(
			'full-width' => array(__('Full Width', 'surfaceslider'), false),
			'fixed' => array(__('Fixed', 'surfaceslider'), true),
		),
		'boolean' => array(
			1 => array(__('Yes', 'surfaceslider'), true),
			0 => array(__('No', 'surfaceslider'), false),
		),
	);
	?>
	
	<?php if($edit) { ?>
		<input type="text" id="ss-slider-name" placeholder="<?php _e('Slider Name', 'surfaceslider'); ?>" value="<?php echo $slider->name; ?>" />
	<?php
	}
	else { ?>
		<input type="text" id="ss-slider-name" placeholder="<?php _e('Slider Name', 'surfaceslider'); ?>" />
	<?php } ?>
	
	<br />
	<br />
	
	<strong><?php _e('Alias:', 'surfaceslider'); ?></strong>
	<?php if($edit) { ?>
		<span id="ss-slider-alias"><?php echo $slider->alias; ?></span>
	<?php
	}
	else { ?>
		<span id="ss-slider-alias"></span>
	<?php } ?>
	
	<br />
	<br />
	
	<strong><?php _e('Shortcode:', 'surfaceslider'); ?></strong>	
	<?php if($edit) { ?>
		<span id="ss-slider-shortcode">[surfaceslider alias="<?php echo $slider->alias; ?>"]</span>
	<?php
	}
	else { ?>
		<span id="ss-slider-shortcode"></span>
	<?php } ?>
	
	<br />
	<br />
	
	<table class="ss-slider-settings-list ss-table">
		<thead>
			<tr>
				<th><?php _e('Option', 'surfaceslider'); ?></th>
				<th><?php _e('Parameter', 'surfaceslider'); ?></th>
				<th><?php _e('Description', 'surfaceslider'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="ss-name"><?php _e('Layout', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-layout">
						<?php
						foreach($slider_select_options['layout'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->layout == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('Modify the layout type of the slider.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Responsive', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-responsive">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->responsive == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('The slider will be adapted to the screen size.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Start Width', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if(!$edit) echo '<input id="ss-slider-startWidth" type="text" value="1170" />';
					else echo '<input id="ss-slider-startWidth" type="text" value="' . $slider->startWidth .'" />';
					?>
					px
				</td>
				<td class="ss-description">
					<?php _e('The content initial width of the slider.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Start Height', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if(!$edit) echo '<input id="ss-slider-startHeight" type="text" value="500" />';
					else echo '<input id="ss-slider-startHeight" type="text" value="' . $slider->startHeight .'" />';
					?>
					px
				</td>
				<td class="ss-description">
					<?php _e('The content initial height of the slider.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Automatic Slide', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-automaticSlide">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->automaticSlide == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('The slides loop is automatic.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Show Controls', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-showControls">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->showControls == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('Show the previous and next arrows.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Show Navigation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-showNavigation">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->showNavigation == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('Show the links buttons to change slide.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Enable swipe and drag', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-enableSwipe">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->enableSwipe == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('Enable swipe left, swipe right, drag left, drag right commands.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Show Progress Bar', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-showProgressBar">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->showProgressBar == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('Draw the progress bar during the slide execution.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Pause on Hover', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select id="ss-slider-pauseOnHover">
						<?php
						foreach($slider_select_options['boolean'] as $key => $value) {
							echo '<option value="' . $key . '"';
							if((!$edit && $value[1]) || ($edit && $slider->pauseOnHover == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('Pause the current slide when hovered.', 'surfaceslider'); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>