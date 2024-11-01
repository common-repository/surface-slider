<?php
function surfaceslider_printElements($edit, $slider, $slide, $elements) {
?>
	<div class="ss-elements">

		<div
		class="ss-slide-editing-area"
		<?php if($edit && $slide): ?>
			<?php
			if($slide->background_type_image != 'none') {
				echo 'data-background-image-src="' . $slide->background_type_image . '"';
			}
			?>
			style="
			width: <?php echo $slider->startWidth; ?>px;
			height: <?php echo $slider->startHeight; ?>px;
			background-image: url('<?php echo $slide->background_type_image; ?>');
			background-color: <?php echo $slide->background_type_color == 'transparent' ? 'rgb(255, 255, 255)' : $slide->background_type_color; ?>;
			background-position-x: <?php echo $slide->background_propriety_position_x; ?>;
			background-position-y: <?php echo $slide->background_propriety_position_y; ?>;
			background-repeat: <?php echo $slide->background_repeat; ?>;
			background-size: <?php echo $slide->background_propriety_size; ?>;
			<?php echo stripslashes($slide->custom_css); ?>
			"
		<?php endif; ?>
		>		
			<?php
			if($edit && $elements != NULL) {
				foreach($elements as $element) {
					if($element->link != '') {
						$target = $element->link_new_tab == 1 ? 'target="_blank"' : '';
						
						$link_output = '<a' . "\n" .
						'class="ss-element ss-' . $element->type . '-element"' . "\n" .
						'href="' . stripslashes($element->link) . '"' . "\n" .
						$target . "\n" .
						'style="' .
						'z-index: ' . $element->z_index . ';' . "\n" .
						'top: ' . $element->data_top . 'px;' . "\n" .
						'left: ' . $element->data_left . 'px;' . "\n" .
						'">' .  "\n";
						
						echo $link_output;
					}
					
					switch($element->type) {
						case 'text':
							?>
							<div
							style="
							<?php
							if($element->link == '') {
								echo 'z-index: ' . $element->z_index . ';';
								echo 'left: ' . $element->data_left . 'px;';
								echo 'top: ' . $element->data_top . 'px;';
							}
							echo stripslashes($element->custom_css);
							?>
							"
							<?php
							if($element->link == '') {
								echo 'class="ss-element ss-text-element"';
							}
							?>
							>
							<?php echo stripslashes($element->inner_html); ?>
							</div>
							<?php
						break;
						case 'image':
							?>
							<img
							src="<?php echo $element->image_src; ?>"
							alt="<?php echo $element->image_alt; ?>"
							style="
							<?php
							if($element->link == '') {
								echo 'z-index: ' . $element->z_index . ';';
								echo 'left: ' . $element->data_left . 'px;';
								echo 'top: ' . $element->data_top . 'px;';
							}
							echo stripslashes($element->custom_css);
							?>
							"
							<?php
							if($element->link == '') {
								echo 'class="ss-element ss-image-element"';
							}
							?>
							/>
							<?php
						break;
					}
					
					if($element->link != '') {
						echo '</a>' . "\n";
					}
				}
			}
			?>
		</div>
		
		<br />
		<br />

		<div class="ss-elements-actions">
			<div style="float: left;">		
				<a class="ss-add-text-element ss-button ss-is-warning"><?php _e('Add text', 'surfaceslider'); ?></a>
				<a class="ss-add-image-element ss-button ss-is-warning"><?php _e('Add image', 'surfaceslider'); ?></a>
			</div>
			<div style="width: auto; position: fixed; bottom: 11px; z-index: 2; left: 395px;">
				<a class="ss-live-preview ss-button ss-is-success"><?php _e('Live preview', 'surfaceslider'); ?></a>
				<a class="ss-delete-element ss-button ss-is-danger ss-is-disabled"><?php _e('Delete element', 'surfaceslider'); ?></a>
				<!-- <a class="ss-duplicate-element ss-button ss-is-primary ss-is-disabled"><?php _e('Duplicate element', 'surfaceslider'); ?></a> -->
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<br />
		<br />
		
		<div class="ss-elements-list">
			<?php
			if($edit && $elements != NULL) {
				foreach($elements as $element) {
					switch($element->type) {
						case 'text':
							echo '<div class="ss-element-settings ss-text-element-settings" style="display: none;">';
							surfaceslider_printTextElement($element);
							echo '</div>';
							break;
						case 'image':
							echo '<div class="ss-element-settings ss-image-element-settings" style="display: none;">';
							surfaceslider_printImageElement($element);
							echo '</div>';
							break;
					}
				}
			}
			echo '<div class="ss-void-element-settings ss-void-text-element-settings ss-element-settings ss-text-element-settings">';
			surfaceslider_printTextElement(false);
			echo '</div>';
			echo '<div class="ss-void-element-settings ss-void-image-element-settings ss-element-settings ss-image-element-settings">';
			surfaceslider_printImageElement(false);
			echo '</div>';
			?>
		</div>

	</div>
<?php
}

function surfaceslider_printTextElement($element) {
	$void = !$element ? true : false;
	
	$animations = array(
		'slideDown' => array(__('Slide down', 'surfaceslider'), false),
		'slideUp' => array(__('Slide up', 'surfaceslider'), false),
		'slideLeft' => array(__('Slide left', 'surfaceslider'), false),
		'slideRight' => array(__('Slide right', 'surfaceslider'), false),
		'fade' => array(__('Fade', 'surfaceslider'), true),
		'fadeDown' => array(__('Fade down', 'surfaceslider'), false),
		'fadeUp' => array(__('Fade up', 'surfaceslider'), false),
		'fadeLeft' => array(__('Fade left', 'surfaceslider'), false),
		'fadeRight' => array(__('Fade right', 'surfaceslider'), false),
		'fadeSmallDown' => array(__('Fade small down', 'surfaceslider'), false),
		'fadeSmallUp' => array(__('Fade small up', 'surfaceslider'), false),
		'fadeSmallLeft' => array(__('Fade small left', 'surfaceslider'), false),
		'fadeSmallRight' => array(__('Fade small right', 'surfaceslider'), false),
	);
	
	?>
	<table class="ss-element-settings-list ss-text-element-settings-list ss-table">
		<thead>
			<tr class="odd-row">
				<th colspan="3"><?php _e('Element Options', 'surfaceslider'); ?></th>
			</tr>
		</thead>
		
		<tbody>
			<tr class="ss-table-header">
				<td><?php _e('Option', 'surfaceslider'); ?></td>
				<td><?php _e('Parameter', 'surfaceslider'); ?></td>
				<td><?php _e('Description', 'surfaceslider'); ?></td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Text', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php					
					if($void) echo '<textarea class="ss-element-inner_html">' . __('Text element', 'surfaceslider') . '</textarea>';
					else echo '<textarea class="ss-element-inner_html">' . stripslashes($element->inner_html) . '</textarea>';
					?>
				</td>
				<td class="ss-description">
					<?php _e('Write the text or the HTML.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Left', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_left" type="text" value="0" />';
					else echo '<input class="ss-element-data_left" type="text" value="' . $element->data_left .'" />';
					?>
					px
				</td>
				<td class="ss-description">
					<?php _e('Left distance in px from the start width.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Top', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_top" type="text" value="0" />';
					else echo '<input class="ss-element-data_top" type="text" value="' . $element->data_top .'" />';
					?>
					px
				</td>
				<td class="ss-description">
					<?php _e('Top distance in px from the start height.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Z - index', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-z_index" type="text" value="1" />';
					else echo '<input class="ss-element-z_index" type="text" value="' . $element->z_index .'" />';
					?>
				</td>
				<td class="ss-description">
					<?php _e('An element with an high z-index will cover an element with a lower z-index if they overlap.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Delay', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_delay" type="text" value="0" />';
					else echo '<input class="ss-element-data_delay" type="text" value="' . $element->data_delay .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the element wait before the entrance.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Time', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_time" type="text" value="all" />';
					else echo '<input class="ss-element-data_time" type="text" value="' . $element->data_time .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the element be displayed during the slide execution. Write "all" to set the entire time.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('In animation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select class="ss-element-data_in">
						<?php
						foreach($animations as $key => $value) {
							echo '<option value="' . $key . '"';
							if(($void && $value[1]) || (!$void && $element->data_in == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('The in animation of the element.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Out animation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select class="ss-element-data_out">
						<?php
						foreach($animations as $key => $value) {
							echo '<option value="' . $key . '"';
							if(($void && $value[1]) || (!$void && $element->data_out == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
					<br />
					<?php
					if($void) echo '<input class="ss-element-data_ignoreEaseOut" type="checkbox" />' . __('Disable synchronization with slide out animation', 'surfaceslider');
					else {
						if($element->data_ignoreEaseOut) {
							echo '<input class="ss-element-data_ignoreEaseOut" type="checkbox" checked />' . __('Disable synchronization with slide out animation', 'surfaceslider');
						}
						else {
							echo '<input class="ss-element-data_ignoreEaseOut" type="checkbox" />' . __('Disable synchronization with slide out animation', 'surfaceslider');
						}
					}
					?>
				</td>
				<td class="ss-description">
					<?php _e('The out animation of the element.<br /><br />Disable synchronization with slide out animation: if not checked, the slide out animation won\'t start until all the elements that have this option unchecked are animated out.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Ease in', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_easeIn" type="text" value="300" />';
					else echo '<input class="ss-element-data_easeIn" type="text" value="' . $element->data_easeIn .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the in animation take.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Ease out', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_easeOut" type="text" value="300" />';
					else echo '<input class="ss-element-data_easeOut" type="text" value="' . $element->data_easeOut .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the out animation take.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Link', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-link" type="text" value="" />';
					else echo '<input class="ss-element-link" type="text" value="' . stripslashes($element->link) .'" />';
					?>
					<br />
					<?php
					if($void) echo '<input class="ss-element-link_new_tab" type="checkbox" />' . __('Open link in a new tab', 'surfaceslider');
					else {
						if($element->link_new_tab) {
							echo '<input class="ss-element-link_new_tab" type="checkbox" checked />' . __('Open link in a new tab', 'surfaceslider');
						}
						else {
							echo '<input class="ss-element-link_new_tab" type="checkbox" />' . __('Open link in a new tab', 'surfaceslider');
						}
					}
					?>
				</td>
				<td class="ss-description">
					<?php _e('Open the link (e.g.: http://www.google.it) on click. Leave it empty if you don\'t want it.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Custom CSS', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<textarea class="ss-element-custom_css"></textarea>';
					else echo '<textarea class="ss-element-custom_css">' . stripslashes($element->custom_css) . '</textarea>';
					?>
				</td>
				<td class="ss-description">
					<?php _e('Style the element.', 'surfaceslider'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function surfaceslider_printImageElement($element) {
	$void = !$element ? true : false;
	
	$animations = array(
		'slideDown' => array(__('Slide down', 'surfaceslider'), false),
		'slideUp' => array(__('Slide up', 'surfaceslider'), false),
		'slideLeft' => array(__('Slide left', 'surfaceslider'), false),
		'slideRight' => array(__('Slide right', 'surfaceslider'), false),
		'fade' => array(__('Fade', 'surfaceslider'), true),
		'fadeDown' => array(__('Fade down', 'surfaceslider'), false),
		'fadeUp' => array(__('Fade up', 'surfaceslider'), false),
		'fadeLeft' => array(__('Fade left', 'surfaceslider'), false),
		'fadeRight' => array(__('Fade right', 'surfaceslider'), false),
		'fadeSmallDown' => array(__('Fade small down', 'surfaceslider'), false),
		'fadeSmallUp' => array(__('Fade small up', 'surfaceslider'), false),
		'fadeSmallLeft' => array(__('Fade small left', 'surfaceslider'), false),
		'fadeSmallRight' => array(__('Fade small right', 'surfaceslider'), false),
	);
	
	?>
	<table class="ss-element-settings-list ss-image-element-settings-list ss-table">
		<thead>
			<tr class="odd-row">
				<th colspan="3"><?php _e('Element Options', 'surfaceslider'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="ss-table-header">
				<td><?php _e('Option', 'surfaceslider'); ?></td>
				<td><?php _e('Parameter', 'surfaceslider'); ?></td>
				<td><?php _e('Description', 'surfaceslider'); ?></td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Modify image', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-image-element-upload-button ss-button ss-is-default" type="button" value="' . __('Open gallery', 'surfaceslider') . '" />';
					else echo '<input data-src="' . $element->image_src . '" data-alt="' . $element->image_alt . '" class="ss-image-element-upload-button ss-button ss-is-default" type="button" value="' . __('Open gallery', 'surfaceslider') . '" />';
					?>
				</td>
				<td class="ss-description">
					<?php _e('Change the image source or the alt text.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Left', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_left" type="text" value="0" />';
					else echo '<input class="ss-element-data_left" type="text" value="' . $element->data_left .'" />';
					?>
					px
				</td>
				<td class="ss-description">
					<?php _e('Left distance in px from the start width.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Top', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_top" type="text" value="0" />';
					else echo '<input class="ss-element-data_top" type="text" value="' . $element->data_top .'" />';
					?>
					px
				</td>
				<td class="ss-description">
					<?php _e('Top distance in px from the start height.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Z - index', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-z_index" type="text" value="1" />';
					else echo '<input class="ss-element-z_index" type="text" value="' . $element->z_index .'" />';
					?>
				</td>
				<td class="ss-description">
					<?php _e('An element with an high z-index will cover an element with a lower z-index if they overlap.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Delay', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_delay" type="text" value="0" />';
					else echo '<input class="ss-element-data_delay" type="text" value="' . $element->data_delay .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the element wait before the entrance.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Time', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_time" type="text" value="all" />';
					else echo '<input class="ss-element-data_time" type="text" value="' . $element->data_time .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the element be displayed during the slide execution. Write "all" to set the entire time.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('In animation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select class="ss-element-data_in">
						<?php
						foreach($animations as $key => $value) {
							echo '<option value="' . $key . '"';
							if(($void && $value[1]) || (!$void && $element->data_in == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
				</td>
				<td class="ss-description">
					<?php _e('The in animation of the element.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Out animation', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<select class="ss-element-data_out">
						<?php
						foreach($animations as $key => $value) {
							echo '<option value="' . $key . '"';
							if(($void && $value[1]) || (!$void && $element->data_out == $key)) {
								echo ' selected';
							}
							echo '>' . $value[0] . '</option>';
						}
						?>
					</select>
					<br />
					<?php
					if($void) echo '<input class="ss-element-data_ignoreEaseOut" type="checkbox" />' . __('Disable synchronization with slide out animation', 'surfaceslider');
					else {
						if($element->data_ignoreEaseOut) {
							echo '<input class="ss-element-data_ignoreEaseOut" type="checkbox" checked />' . __('Disable synchronization with slide out animation', 'surfaceslider');
						}
						else {
							echo '<input class="ss-element-data_ignoreEaseOut" type="checkbox" />' . __('Disable synchronization with slide out animation', 'surfaceslider');
						}
					}
					?>
				</td>
				<td class="ss-description">
					<?php _e('The out animation of the element.<br /><br />Disable synchronization with slide out animation: if not checked, the slide out animation won\'t start until all the elements that have this option unchecked are animated out.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Ease in', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_easeIn" type="text" value="300" />';
					else echo '<input class="ss-element-data_easeIn" type="text" value="' . $element->data_easeIn .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the in animation take.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Ease out', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-data_easeOut" type="text" value="300" />';
					else echo '<input class="ss-element-data_easeOut" type="text" value="' . $element->data_easeOut .'" />';
					?>
					ms
				</td>
				<td class="ss-description">
					<?php _e('How long will the out animation take.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Link', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<input class="ss-element-link" type="text" value="" />';
					else echo '<input class="ss-element-link" type="text" value="' . stripslashes($element->link) .'" />';
					?>
					<br />
					<?php
					if($void) echo '<input class="ss-element-link_new_tab" type="checkbox" />' . __('Open link in a new tab', 'surfaceslider');
					else {
						if($element->link_new_tab) {
							echo '<input class="ss-element-link_new_tab" type="checkbox" checked />' . __('Open link in a new tab', 'surfaceslider');
						}
						else {
							echo '<input class="ss-element-link_new_tab" type="checkbox" />' . __('Open link in a new tab', 'surfaceslider');
						}
					}
					?>
				</td>
				<td class="ss-description">
					<?php _e('Open the link (e.g.: http://www.google.it) on click. Leave it empty if you don\'t want it.', 'surfaceslider'); ?>
				</td>
			</tr>
			<tr>
				<td class="ss-name"><?php _e('Custom CSS', 'surfaceslider'); ?></td>
				<td class="ss-content">
					<?php
					if($void) echo '<textarea class="ss-element-custom_css"></textarea>';
					else echo '<textarea class="ss-element-custom_css">' . stripslashes($element->custom_css) . '</textarea>';
					?>
				</td>
				<td class="ss-description">
					<?php _e('Style the element.', 'surfaceslider'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}
?>