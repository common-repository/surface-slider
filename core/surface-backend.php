<?php
global $wpdb;
$sliders = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'surfaceslider_sliders');

if(!$sliders) {
	echo '<div class="ss-no-sliders">';
	_e('No Sliders found. Please add a new one.', 'surfaceslider');
	echo '</div>';
	echo '<br /><br />';
}
else {
	?>
	
	<table class="ss-sliders-list ss-table">
		<thead>
			<tr class="ss-table-header">
				<th><?php _e('ID', 'surfaceslider'); ?></th>
				<th><?php _e('Name', 'surfaceslider'); ?></th>
				<th><?php _e('Alias', 'surfaceslider'); ?></th>
				<th><?php _e('Shortcode', 'surfaceslider'); ?></th>
				<th><?php _e('Actions', 'surfaceslider'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($sliders as $slider) {
				echo '<tr>';
				echo '<td>' . $slider->id . '</td>';
				echo '<td><a href="?page=surfaceslider&view=edit&id=' . $slider->id . '">' . $slider->name . '</a></td>';
				echo '<td>' . $slider->alias . '</td>';
				echo '<td>[surfaceslider alias="' . $slider->alias . '"]</td>';
				echo '<td>
					<a class="ss-edit-slider ss-button ss-button ss-is-success" href="?page=surfaceslider&view=edit&id=' . $slider->id . '">' . __('Edit Slider', 'surfaceslider') . '</a>
					<a class="ss-delete-slider ss-button ss-button ss-is-danger" href="javascript:void(0)" data-delete="' . $slider->id . '">' . __('Delete Slider', 'surfaceslider') . '</a>
				</td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
	<?php
}
?>

<br />
<a class="ss-button ss-is-primary ss-add-slider" href="?page=surfaceslider&view=add"><?php _e('Add Slider', 'surfaceslider'); ?></a>