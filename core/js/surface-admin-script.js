(function($) {
	$(window).load(function() {
		
		// Run tabs
		$('.ss-tabs').tabs({
			show: function(event, ui) {
				var $target = $(ui.panel);
				if(target.hasClass('ss-tabs-fade')) {
					$('.content:visible').effect(
						'explode',
						{},
						1500,
						function(){
							$target.fadeIn(300);
						}
					);
				}
			}
		});
		
		// Run draggables
		surfaceslider_draggableElements();
		
		function surfaceslider_showSuccess() {
			var target = $('.ss-admin .ss-message.ss-message-ok');
			target.css({
				'display' : 'block',
				'opacity' : 0,
			});
			target.animate({
				'opacity' : 1,
			}, 300)
			.delay(2000)
			.animate({
				'opacity' : 0,
			}, 300, function() {
				target.css('display', 'none');
			});
		}
		
		function surfaceslider_showError() {
			var target = $('.ss-admin .ss-message.ss-message-error');
			target.css({
				'display' : 'block',
				'opacity' : 0,
			});
			target.animate({
				'opacity' : 1,
			}, 300)
			.delay(2000)
			.animate({
				'opacity' : 0,
			}, 300, function() {
				target.css('display', 'none');
			});
		}
		
		/*************/
		/** SLIDERS **/
		/*************/
		
		// Set Alias	
		$('.ss-slider').find('#ss-slider-name').keyup(function() {
			var alias = surfaceslider_getAlias();
			$('.ss-slider').find('#ss-slider-alias').text(alias);
		});
		
		// Set shortcode
		$('.ss-slider').find('#ss-slider-name').keyup(function() {
			var alias = surfaceslider_getAlias();
			var shortcode = '';
			shortcode += '[surfaceslider alias="';
			shortcode += alias;
			shortcode += '"]';
			if(alias != '') {
				$('.ss-slider').find('#ss-slider-shortcode').text(shortcode);
			}
			else {
				$('.ss-slider').find('#ss-slider-shortcode').text('');
			}
		});
		
		// Set the new sizes of the editing area and of the slider if changing values
		$('.ss-admin #ss-slider-settings .ss-slider-settings-list #ss-slider-startWidth').keyup(function() {
			surfaceslider_setSlidesEditingAreaSizes();
		});
		$('.ss-admin #ss-slider-settings .ss-slider-settings-list #ss-slider-startHeight').keyup(function() {
			surfaceslider_setSlidesEditingAreaSizes();
		});
		
		// Get the alias starting form the name
		function surfaceslider_getAlias() {
			var slider_name = $('.ss-slider').find('#ss-slider-name').val();
			var slider_alias = slider_name.toLowerCase();
			slider_alias = slider_alias.replace(/ /g, '_');
			return slider_alias;
		}
		
		/************/
		/** SLIDES **/
		/************/
		
		var slides_number = $('.ss-admin #ss-slides .ss-slide-tabs ul li').length - 1;
		
		// Run sortable
		var slide_before; // Contains the index before the sorting
		var slide_after; // Contains the index after the sorting
		$('.ss-slide-tabs .ss-sortable').sortable({
			items: 'li:not(.ui-state-disabled)',
			cancel: '.ui-state-disabled',
			
			// Store the actual index
			start: function(event, ui) {
				slide_before = $(ui.item).index();
			},
			
			// Change the .ss-slide order based on the new index and rename the tabs
			update: function(event, ui) {
				// Store the new index
				slide_after = $(ui.item).index();
				
				// Change the slide position
				var slide = $('.ss-admin #ss-slides .ss-slides-list .ss-slide:eq(' + slide_before + ')');			
				var after = $('.ss-admin #ss-slides .ss-slides-list .ss-slide:eq(' + slide_after + ')');			
				if(slide_before < slide_after) {
					slide.insertAfter(after);
				}
				else {
					slide.insertBefore(after);
				}
				
				// Rename all the tabs
				$('.ss-admin #ss-slides .ss-slide-tabs ul li').each(function() {
					var temp = $(this);
					if(!temp.find('a').hasClass('ss-add-new')) {
						temp.find('a').text(surfaceslider_translations.slide + (temp.index() + 1));
					}
				});
			}
		});
		$('.ss-slide-tabs .ss-sortable li').disableSelection();
		
		// Show the slide when clicking on the link
		$('.ss-admin #ss-slides .ss-slide-tabs ul li a').live('click', function() {
			// Do only if is not click add new
			if($(this).parent().index() != slides_number) {
				// Hide all tabs
				$('.ss-admin #ss-slides .ss-slides-list .ss-slide').css('display', 'none');
				var tab = $(this).parent().index();
				$('.ss-admin #ss-slides .ss-slides-list .ss-slide:eq(' + tab + ')').css('display', 'block');
				
				// Active class
				$('.ss-admin #ss-slides .ss-slide-tabs ul li').removeClass('active');
				$(this).parent().addClass('active');
			}
		});
		
		// Add new
		function surfaceslider_addSlide() {
			var add_btn = $('.ss-admin #ss-slides .ss-add-new');
			
			var void_slide = $('.ss-admin #ss-slides .ss-void-slide').html();
			// Insert the link at the end of the list
			add_btn.parent().before('<li class="ui-state-default"><a>' + surfaceslider_translations.slide + ' <span class="ss-slide-index">' + (slides_number + 1) + '</span></a><span class="ss-close"></span></li>');
			// jQuery UI tabs are not working here. For now, just use a manual created tab
			$('.ss-admin #ss-slides .ss-slide-tab').tabs('refresh');
			// Create the slide
			$('.ss-admin #ss-slides .ss-slides-list').append('<div class="ss-slide">' + void_slide + '</div>');
			slides_number++;
			
			// Open the tab just created
			var tab_index = add_btn.parent().index() - 1;
			$('.ss-admin #ss-slides .ss-slide-tabs ul li').eq(tab_index).find('a').click();
			
			// Active class
			$('.ss-admin #ss-slides .ss-slide-tabs ul li').removeClass('active');
			$('.ss-admin #ss-slides .ss-slide-tabs ul li').eq(tab_index).addClass('active');
			
			// Set editing area sizes
			surfaceslider_setSlidesEditingAreaSizes();
			
			surfaceslider_slidesColorPicker();
		}
		
		// Add new on click
		$('.ss-admin #ss-slides .ss-add-new').click(function() {
			if(slides_number < 2) {
				surfaceslider_addSlide();
			}
		});	
		// Also add a new slide if slides_number == 0
		if(slides_number == 0) {
			surfaceslider_addSlide();
		}
		else {
			$('.ss-admin #ss-slides .ss-slide-tabs ul li').eq(0).find('a').click();
		}
		
		// Delete
		$('.ss-admin #ss-slides .ss-slide-tabs ul li .ss-close').live('click', function() {
			if($('.ss-admin #ss-slides .ss-slide-tabs ul li').length <= 2) {
				alert(surfaceslider_translations.slide_delete_just_one);
				return;
			}
		
			var confirm = window.confirm(surfaceslider_translations.slide_delete_confirm);
			if(!confirm) {
				return;
			}
			
			slides_number--;
			
			var slide_index = $(this).parent().index();
			
			// If is deleting the current viewing slide, set the first as active
			if($('.ss-admin #ss-slides .ss-slide-tabs ul li').eq(slide_index).hasClass('active') && slides_number != 0) {
				$('.ss-admin #ss-slides .ss-slide-tabs ul li').eq(0).addClass('active');
				$('.ss-admin #ss-slides .ss-slides-list .ss-slide').css('display', 'none');
				$('.ss-admin #ss-slides .ss-slides-list .ss-slide').eq(0).css('display', 'block');			
			}
			
			// Remove the anchor
			$(this).parent().remove();
			// Remove the slide itself
			$('.ss-admin #ss-slides .ss-slides-list .ss-slide').eq(slide_index).remove();
			
			// Scale back all the slides text
			for(var i = slide_index; i < slides_number; i++) {
				var slide = $('.ss-admin #ss-slides .ss-slide-tabs ul li').eq(i);
				var indx = parseInt(slide.find('.ss-slide-index').text());
				slide.find('.ss-slide-index').text(indx - 1);
			}
		});
		
		// Set correct size for the editing area
		function surfaceslider_setSlidesEditingAreaSizes() {
			var width = parseInt($('.ss-admin #ss-slider-settings .ss-slider-settings-list #ss-slider-startWidth').val());
			var height = parseInt($('.ss-admin #ss-slider-settings .ss-slider-settings-list #ss-slider-startHeight').val());
			
			$('.ss-admin #ss-slides .ss-slide .ss-slide-editing-area').css({
				'width' : width,
				'height' : height,
			});
			
			$('.ss-admin').css({
				'width' : width,
			});
		}
		
		surfaceslider_slidesColorPicker();
		
		// Run background color picker
		function surfaceslider_slidesColorPicker() {
			$('.ss-admin #ss-slides .ss-slides-list .ss-slide-settings-list .ss-slide-background_type_color-picker-input').wpColorPicker({
				// a callback to fire whenever the color changes to a valid color
				change: function(event, ui){
					// Change only if the color picker is the user choice
					var btn = $(this);
					if(btn.closest('.ss-content').find('input[name="ss-slide-background_type_color"]:checked').val() == '1') {
						var area = btn.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');
						area.css('background-color', ui.color.toString());
					}
				},
				// a callback to fire when the input is emptied or an invalid color
				clear: function() {},
				// hide the color picker controls on load
				hide: true,
				// show a group of common colors beneath the square
				// or, supply an array of colors to customize further
				palettes: true
			});
		}
		
		// Set background color (transparent or color-picker)
		$('.ss-admin #ss-slides').on('change', '.ss-slides-list .ss-slide-settings-list input[name="ss-slide-background_type_color"]:radio', function() {
			var btn = $(this);
			var area = btn.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');
			
			if(btn.val() == '0') {
				area.css('background-color', '#fff');
			}
			else {
				var color_picker_value = btn.closest('.ss-content').find('.wp-color-result').css('background-color');
				area.css('background-color', color_picker_value);
			}
		});
		
		// Set background image (none or image)
		$('.ss-admin #ss-slides').on('change', '.ss-slides-list .ss-slide-settings-list input[name="ss-slide-background_type_image"]:radio', function() {
			var btn = $(this);
			var area = btn.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');
			
			if(btn.val() == '0') {
				area.css('background-image', 'none');
			}
			else {
				var slide_parent = $(this).closest('.ss-slide');
				surfaceslider_addSlideImageBackground(slide_parent);
			}
		});
		
		// Set Background image (the upload function)
		$('.ss-admin #ss-slides').on('click', '.ss-slides-list .ss-slide-settings-list .ss-slide-background_type_image-upload-button', function() {
			var btn = $(this);
			if(btn.closest('.ss-content').find('input[name="ss-slide-background_type_image"]:checked').val() == '1') {
				var slide_parent = $(this).closest('.ss-slide');
				surfaceslider_addSlideImageBackground(slide_parent);
			}
		});
		function surfaceslider_addSlideImageBackground(slide_parent) {
			var area = slide_parent.find('.ss-slide-editing-area');
			
			// Upload
			var file_frame;

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame.state().get('selection').first().toJSON();

			  // Do something with attachment.id and/or attachment.url here
			  var image_src = attachment.url;
			  var image_alt = attachment.alt;
			  
			  // Set background
			  area.css('background-image', 'url("' + image_src + '")');
			  // I add a data with the src because, is not like images (when there is only the src link), the background contains the url('') string that is very annoying when we will get the content
			  area.data('background-image-src', image_src);
			});

			// Finally, open the modal
			file_frame.open();	
		}
		
		// Background propriety: repeat or no-repeat
		$('.ss-admin #ss-slides').on('change', '.ss-slides-list .ss-slide-settings-list input[name="ss-slide-background_repeat"]:radio', function() {
			var btn = $(this);
			var area = btn.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');
			
			if(btn.val() == '0') {
				area.css('background-repeat', 'no-repeat');
			}
			else {
				area.css('background-repeat', 'repeat');
			}
		});
		
		// Background propriety: positions x and y
		$('.ss-admin #ss-slides').on('keyup', '.ss-slides-list .ss-slide-settings-list .ss-slide-background_propriety_position_x', function() {
			var text = $(this);
			var val = text.val();
			var area = text.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');

			area.css('background-position-x', val);		
		});
		$('.ss-admin #ss-slides').on('keyup', '.ss-slides-list .ss-slide-settings-list .ss-slide-background_propriety_position_y', function() {
			var text = $(this);
			var val = text.val();
			var area = text.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');

			area.css('background-position-y', val);		
		});
		
		// Background propriety: size
		$('.ss-admin #ss-slides').on('keyup', '.ss-slides-list .ss-slide-settings-list .ss-slide-background_propriety_size', function() {
			var text = $(this);
			var val = text.val();
			var area = text.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');

			area.css('background-size', val);		
		});
		
		// Apply custom CSS
		$('.ss-admin #ss-slides').on('keyup', '.ss-slides-list .ss-slide-settings-list .ss-slide-custom_css', function() {
			var text = $(this);
			var area = text.closest('.ss-slide').find('.ss-elements .ss-slide-editing-area');
			var css = text.val();
			
			// Save current styles
			var width = area.css('width');
			var height = area.css('height');
			var background_image = area.css('background-image');
			var background_color = area.css('background-color');
			var background_position_x = area.css('background-position-x');
			var background_position_y = area.css('background-position-y');
			var background_repeat = area.css('background-repeat');
			var background_size = area.css('background-size');
			
			// Apply CSS
			area.attr('style', css);
			area.css({
				'width' : width,
				'height' : height,
				'background-image' : background_image,
				'background-color' : background_color,
				'background-position-x' : background_position_x,
				'background-position-y' : background_position_y,
				'background-repeat' : background_repeat,
				'background-size' : background_size
			});
		});		
		
		/**************/
		/** ELEMENTS **/
		/**************/
		
		// GENERAL
		
		// Make draggable
		function surfaceslider_draggableElements() {
			$('.ss-admin .ss-elements .ss-element').draggable({
				'containment' : 'parent',
				
				start: function() {
					// Select when dragging
					surfaceslider_selectElement($(this));
				},
				
				drag: function(){
					// Set left and top positions on drag to the textbox
					var position = $(this).position();
					var left = position.left;
					var top = position.top;
					var index = $(this).index();
					
					$(this).closest('.ss-elements').find('.ss-elements-list .ss-element-settings:eq(' + index + ') .ss-element-data_left').val(left);
					$(this).closest('.ss-elements').find('.ss-elements-list .ss-element-settings:eq(' + index + ') .ss-element-data_top').val(top);
				},
			});
		}
		
		// Selects an element, shows its options and makes the delete element button available
		$('.ss-admin #ss-slides').on('click', '.ss-slide .ss-elements .ss-slide-editing-area .ss-element', function(e) {
			// Do not click the editing-area
			e.stopPropagation();
			
			// Do not open links
			e.preventDefault();
			
			surfaceslider_selectElement($(this));
		});
		function surfaceslider_selectElement(element) {
			var index = element.index();
			var slide = element.closest('.ss-slide');		
			var options = slide.find('.ss-elements .ss-elements-list');
			
			// Hide all options - .active class
			options.find('.ss-element-settings').css('display', 'none');
			options.find('.ss-element-settings').removeClass('active');
			
			// Show the correct options + .active class
			options.find('.ss-element-settings:eq(' + index + ')').css('display', 'block');
			options.find('.ss-element-settings:eq(' + index + ')').addClass('active');
			
			// Add .active class to the element in the editing area
			element.parent().children().removeClass('active');
			element.addClass('active');
			
			// Make the delete and the duplicate buttons working
			slide.find('.ss-elements-actions .ss-delete-element').removeClass('ss-is-disabled');
			slide.find('.ss-elements-actions .ss-duplicate-element').removeClass('ss-is-disabled');
		}
		
		// Deselect elements
		$('.ss-admin').on('click', '.ss-slide .ss-elements .ss-slide-editing-area', function() {
			surfaceslider_deselectElements();
		});
		function surfaceslider_deselectElements() {
			$('.ss-admin .ss-slide .ss-elements .ss-slide-editing-area .ss-element').removeClass('active');
			$('.ss-admin .ss-slide .ss-elements .ss-elements-list .ss-element-settings').removeClass('active');		
			$('.ss-admin .ss-slide .ss-elements .ss-elements-list .ss-element-settings').css('display', 'none');		
			
			// Hide delete and duplicate element btns
			$('.ss-admin .ss-slide .ss-elements-actions .ss-delete-element').addClass('ss-is-disabled');
			$('.ss-admin .ss-slide .ss-elements-actions .ss-duplicate-element').addClass('ss-is-disabled');
		}
		
		// Delete element. Remember that the button should be enabled / disabled somewhere else
		function surfaceslider_deleteElement(element) {
			var index = element.index();
			var slide_parent = element.closest('.ss-slide');
			
			element.remove();
			var element_options = slide_parent.find('.ss-elements-list .ss-element-settings:eq(' + index + ')');
			element_options.remove();
			surfaceslider_deselectElements();
		}
		$('.ss-admin #ss-slides').on('click', '.ss-slide .ss-elements .ss-elements-actions .ss-delete-element', function() {
			// Click only if an element is selected
			if($(this).hasClass('.ss-is-disabled')) {
				return;
			}
			
			var slide_parent = $(this).closest('.ss-slide');
			var element = slide_parent.find('.ss-elements .ss-slide-editing-area .ss-element.active');
			surfaceslider_deleteElement(element);
		});
		
		function surfaceslider_duplicateElement(element) {
			var index = element.index();
			var slide_parent = element.closest('.ss-slide');
			
			element.clone().appendTo(element.parent());
			var element_options = slide_parent.find('.ss-elements-list .ss-element-settings').eq(index);
			element_options.clone().insertBefore(element_options.parent().find('.ss-void-text-element-settings'));
			
			surfaceslider_deselectElements();
			surfaceslider_selectElement(element.parent().find('.ss-element').last());
			
			// Clone fixes (Google "jQuery clone() bug")
			var cloned_options = element.parent().find('.ss-element').last().closest('.ss-slide').find('.ss-elements-list .ss-element-settings.active');
			
			cloned_options.find('.ss-element-data_in').val(element_options.find('.ss-element-data_in').val());
			cloned_options.find('.ss-element-data_out').val(element_options.find('.ss-element-data_out').val());
			cloned_options.find('.ss-element-custom_css').val(element_options.find('.ss-element-custom_css').val());			
			if(element_options.hasClass('ss-image-element-settings')) {
				cloned_options.find('.ss-image-element-upload-button').data('src', element_options.find('.ss-image-element-upload-button').data('src'));	
				cloned_options.find('.ss-image-element-upload-button').data('alt', element_options.find('.ss-image-element-upload-button').data('alt'));	
			}
			
			// Make draggable
			surfaceslider_draggableElements();
		}
		$('.ss-admin #ss-slides').on('click', '.ss-slide .ss-elements .ss-elements-actions .ss-duplicate-element', function() {
			// Click only if an element is selected
			if($(this).hasClass('.ss-is-disabled')) {
				return;
			}
			
			var slide_parent = $(this).closest('.ss-slide');
			var element = slide_parent.find('.ss-elements .ss-slide-editing-area .ss-element.active');
			surfaceslider_duplicateElement(element);
		});
		
		// Modify left position
		$('.ss-admin').on('keyup', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-data_left', function() {
			var index = $(this).closest('.ss-element-settings').index();
			$(this).closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('left', parseFloat($(this).val()));
		});
		
		// Modify top position
		$('.ss-admin').on('keyup', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-data_top', function() {
			var index = $(this).closest('.ss-element-settings').index();
			$(this).closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('top', parseFloat($(this).val()));
		});
		
		// Modify z-index
		$('.ss-admin').on('keyup', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-z_index', function() {
			var index = $(this).closest('.ss-element-settings').index();
			$(this).closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('z-index', parseFloat($(this).val()));
		});
		
		// Add / remove link wrapper (fire on textbox edit or on checkbox _target:"blank" edit)
		$('.ss-admin').on('keyup', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-link', function() {
			surfaceslider_editElementsLink($(this));
		});
		$('.ss-admin').on('change', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-link_new_tab', function() {
			var textbox = $(this).parent().find('.ss-element-link');
			surfaceslider_editElementsLink(textbox);
		});
		
		// Wrap - unwrap elements with an <a href="" target="">
		function surfaceslider_editElementsLink(textbox_link) {
			var index = textbox_link.closest('.ss-element-settings').index();
			var copy_attributes = false;
			var reapply_css = false;
			
			if(textbox_link.val() != '' && !textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').hasClass('ss-element')) {
				var link_new_tab = textbox_link.parent().find('.ss-element-link_new_tab').prop('checked') ? 'target="_blank"' : '';
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').wrap('<a href="' + textbox_link.val() + '"' + link_new_tab + ' />');
				copy_attributes = true;
				reapply_css = true;
			}
			else if(textbox_link.val() != '' && textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').hasClass('ss-element')) {
				var link_new_tab = textbox_link.parent().find('.ss-element-link_new_tab').prop('checked') ? true : false;
				
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').attr('href', textbox_link.val());
				
				if(link_new_tab) {
					textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').attr('target', '_blank');
				}
				else {
					textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').removeAttr('target');
				}
				
				copy_attributes = false;
			}
			else if(textbox_link.val() == '' && textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').hasClass('ss-element')) {
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').attr('class', textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').attr('class')).removeClass('ui-draggable');
				
				// Reapply CSS and custom CSS
				applyCustomCss(textbox_link.closest('.ss-element-settings').find('.ss-element-custom_css'));
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').css('top', textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').css('top'));
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').css('left', textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').css('left'));
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').css('z-index', textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').css('z-index'));
				
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').unwrap();
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').parent('a').draggable('destroy');
				copy_attributes = false;
			}
			
			if(copy_attributes) {
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').parent().attr('style', textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').attr('style'));
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').parent().attr('class', textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').attr('class')).removeClass('ui-draggable');
				
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').removeAttr('style');
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').removeAttr('class');
				textbox_link.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').draggable('destroy');
			}
			
			surfaceslider_draggableElements();
			
			if(reapply_css) {
				applyCustomCss(textbox_link.closest('.ss-element-settings').find('.ss-element-custom_css'));
			}
		}
		
		// Apply custom CSS
		$('.ss-admin').on('keyup', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-custom_css', function() {
			applyCustomCss($(this));
		});
		
		function applyCustomCss(textarea) {
			var index = textarea.closest('.ss-element-settings').index();
			// Save current positions
			var left = textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('left');
			var top = textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('top');
			var z_index = textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('z-index');
			
			// Apply CSS
			if(! textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').is('a')) {
				textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').attr('style', textarea.val());
			}
			else {
				textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ') > *').attr('style', textarea.val());
				textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').removeAttr('style');
			}
			textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('top', top);
			textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('left', left);
			textarea.closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')').css('z-index', z_index);			
		}
		
		// TEXT ELEMENTS
		
		// Add text click
		$('.ss-admin #ss-slides').on('click', '.ss-slide .ss-elements .ss-elements-actions .ss-add-text-element', function() {
			var slide_parent = $(this).closest('.ss-slide');
			var text_element_size = slide_parent.find('.ss-text-element').length;
			if(text_element_size < 4){
				surfaceslider_addTextElement(slide_parent);
			}
		});
		
		// Add text. Receives the slide as object
		function surfaceslider_addTextElement(slide_parent) {
			var area = slide_parent.find('.ss-slide-editing-area');
			var settings_div = slide_parent.find('.ss-elements .ss-elements-list .ss-void-text-element-settings');
			var settings = '<div class="ss-element-settings ss-text-element-settings">' + $('.ss-admin .ss-slide .ss-elements .ss-void-text-element-settings').html() + '</div>';
			
			// Insert in editing area
			area.append('<div class="ss-element ss-text-element" style="z-index: 1;">' + surfaceslider_translations.text_element_default_html + '</div>');
			
			// Insert the options
			settings_div.before(settings);
			
			// Make draggable
			surfaceslider_draggableElements();
			
			// Display settings
			surfaceslider_selectElement(area.find('.ss-element').last());
		}
		
		// Modify text
		$('.ss-admin').on('keyup', '.ss-elements .ss-elements-list .ss-element-settings .ss-element-inner_html', function() {
			var index = $(this).closest('.ss-element-settings').index();
			var text_element = $(this).closest('.ss-elements').find('.ss-slide-editing-area .ss-element:eq(' + index + ')');
			
			if(! text_element.is('a')) {
				text_element.html($(this).val());
			}
			else {
				text_element.find('> div').html($(this).val());
			}
		});
		
		// IMAGE ELEMENTS
		
		// Add images click
		$('.ss-admin #ss-slides').on('click', '.ss-slide .ss-elements .ss-elements-actions .ss-add-image-element', function() {
			var slide_parent = $(this).closest('.ss-slide');
			var img_element_size = slide_parent.find('.ss-image-element').length;
			if(img_element_size < 1){
				surfaceslider_addImageElement(slide_parent);
			}
		});
		
		// Upload click
		$('.ss-admin').on('click', '.ss-elements .ss-elements-list .ss-image-element-settings .ss-image-element-upload-button', function() {
			var slide_parent = $(this).closest('.ss-slide');
			surfaceSliderUploadImageElement(slide_parent);
		});
		
		// Add image. Receives the slide as object
		function surfaceslider_addImageElement(slide_parent) {
			var area = slide_parent.find('.ss-slide-editing-area');
			var settings_div = slide_parent.find('.ss-elements .ss-elements-list .ss-void-text-element-settings');
			var settings = '<div class="ss-element-settings ss-image-element-settings">' + $('.ss-admin .ss-slide .ss-elements .ss-void-image-element-settings').html() + '</div>';
			
			// Temporarily insert an element with no src and alt
			// Add the image into the editing area.
			  area.append('<img class="ss-element ss-image-element" src="nothing_now.jpg" style="z-index: 1;" />');
			  
			// Insert the options
			settings_div.before(settings);
			  
			// Make draggable
			surfaceslider_draggableElements();
				
			// Display settings
			surfaceslider_selectElement(area.find('.ss-element').last());
			
			// Upload
			surfaceSliderUploadImageElement(slide_parent);		
		}
		
		function surfaceSliderUploadImageElement(slide_parent) {
			var area = slide_parent.find('.ss-slide-editing-area');
			var settings_div = slide_parent.find('.ss-elements .ss-elements-list .ss-void-text-element-settings');
			var settings = '<div class="ss-element-settings ss-image-element-settings">' + $('.ss-admin .ss-slide .ss-elements .ss-void-image-element-settings').html() + '</div>';
			
			var file_frame;

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame.state().get('selection').first().toJSON();

			  // Do something with attachment.id and/or attachment.url here
			  var image_src = attachment.url;
			  var image_alt = attachment.alt;
			  
			  // Set attributes. If is a link, do the right thing
			  var image = area.find('.ss-image-element.active').last();
			  
			  if(! image.is('a')) {
				  image.attr('src', image_src);
				  image.attr('alt', image_alt);
			  }
			  else {
				image.find('> img').attr('src', image_src);
                image.find('> img').attr('alt', image_alt);
			  }
			  
			  // Set data (will be used in the ajax call)
			  settings_div.parent().find('.ss-element-settings.active .ss-image-element-upload-button').data('src', image_src);
			  settings_div.parent().find('.ss-element-settings.active .ss-image-element-upload-button').data('alt', image_alt);
			});

			// Finally, open the modal
			file_frame.open();
		}
		
		/******************/
		/** LIVE PREVIEW **/
		/******************/
		
		// Live preview click
		$('.ss-admin #ss-slides').on('click', '.ss-slide .ss-elements .ss-elements-actions .ss-live-preview', function() {
			var btn = $(this);
			var slide_parent = btn.closest('.ss-slide');
			
			if(! btn.hasClass('ss-live-preview-running')) {
				btn.addClass('ss-live-preview-running');
				btn.text(surfaceslider_translations.slide_stop_preview);
				$('.ss-overlay').fadeIn();
				surfaceslider_startLivePreview(slide_parent);
			}
			else {
				btn.removeClass('ss-live-preview-running');
				btn.text(surfaceslider_translations.slide_live_preview);
				surfaceslider_stopLivePreview(slide_parent);
				$('.ss-overlay').fadeOut();
			}
		});
		
		function surfaceslider_startLivePreview(slide_parent) {
			surfaceslider_deselectElements();
			
			var area = slide_parent.find('.ss-slide-editing-area');
			
			area.clone().addClass('ss-slide-live-preview-area').insertAfter(area);
			var prev = slide_parent.find('.ss-slide-live-preview-area');
			
			area.css('display', 'none');
			
			// Set elements data and styles
			var elements = prev.find('.ss-element');
			var original_elements = area.closest('.ss-slide').find('.ss-elements .ss-element-settings');
			var i = 0;
			elements.each(function() {
				var element = $(this);
				
				element.attr({
					'data-left' : parseInt(original_elements.eq(i).find('.ss-element-data_left').val()),
					'data-top' : parseInt(original_elements.eq(i).find('.ss-element-data_top').val()),
					'data-delay' : parseInt(original_elements.eq(i).find('.ss-element-data_delay').val()),
					'data-time' : original_elements.eq(i).find('.ss-element-data_time').val(),
					'data-in' : original_elements.eq(i).find('.ss-element-data_in').val(),
					'data-out' : original_elements.eq(i).find('.ss-element-data_out').val(),
					'data-ignore-ease-out' : original_elements.eq(i).find('.ss-element-data_out').prop('checked') ? 1 : 0,
					'data-ease-in' : parseInt(original_elements.eq(i).find('.ss-element-data_easeIn').val()),
					'data-ease-out' : parseInt(original_elements.eq(i).find('.ss-element-data_easeOut').val()),
				});
				
				element.removeAttr('style');
				element.attr('style', original_elements.eq(i).find('.ss-element-custom_css').val());				
				element.css({
					'z-index' : parseInt(original_elements.eq(i).find('.ss-element-z_index').val()),				
				});
				
				element.removeAttr('class');
				
				i++;
			});
			
			// Prepare HTML structure
			prev.wrapInner('<li />');
			prev.wrapInner('<ul />');
			
			// Set slide data and styles
			var slide = prev.find('ul > li');
			var original_slide = area.closest('.ss-slide');
			var content = original_slide.find('.ss-slide-settings-list');
			slide.attr({
				'data-in' : content.find('.ss-slide-data_in').val(),
				'data-out' : content.find('.ss-slide-data_out').val(),
				'data-time' : parseInt(content.find('.ss-slide-data_time').val()),
				'data-ease-in' : parseInt(content.find('.ss-slide-data_easeIn').val()),
				'data-ease-out' : parseInt(content.find('.ss-slide-data_easeOut').val()),
			});
			
			slide.attr('style', content.find('.ss-slide-custom_css').val());
			slide.css({
				'background-image' : area.css('background-image') ,
				'background-color' : area.css('background-color') + "",
				'background-position-x' : content.find('.ss-slide-background_propriety_position_x').val(),
				'background-position-y' : content.find('.ss-slide-background_propriety_position_y').val(),
				'background-repeat' : content.find('input[name="ss-slide-background_repeat"]:checked').val() == '0' ? 'no-repeat' : 'repeat',
				'background-size' : content.find('.ss-slide-background_propriety_size').val(),
			});
			
			var slider = $('.ss-admin .ss-slider #ss-slider-settings');
			
			// Run Surface Slider
			prev.surfaceSlider({
				'layout' : 'fixed',
				'responsive' : false,
				'startWidth' : parseInt(slider.find('#ss-slider-startWidth').val()),
				'startHeight' : parseInt(slider.find('#ss-slider-startHeight').val()),
				
				'automaticSlide' : true,
				'showControls' : false,
				'showNavigation' : false,
				'enableSwipe' : false,
				'showProgressBar' : false,
				'pauseOnHover' : false,
			});
		}
		
		function surfaceslider_stopLivePreview(slide_parent) {
			var area = slide_parent.find('.ss-slide-editing-area');
			var prev = slide_parent.find('.ss-slide-live-preview-area');
			
			prev.remove();
			area.css('display', 'block');
		}
		
		/****************/
		/** AJAX CALLS **/
		/****************/
		
		// Save or update the new slider in the database
		$('.ss-admin .ss-slider .ss-save-settings').click(function() {
			surfaceslider_saveSlider();
		});
		
		// Delete slider
		$('.ss-admin .ss-home .ss-sliders-list .ss-delete-slider').click(function() {
			var confirm = window.confirm(surfaceslider_translations.slider_delete_confirm);
			if(!confirm) {
				return;
			}
			
			surfaceslider_deleteSlider($(this));
		});
		
		// Sends an array with the new or current slider options
		function surfaceslider_saveSlider() {
			var content = $('.ss-admin .ss-slider #ss-slider-settings');
			var options = {
				id : parseInt($('.ss-admin .ss-slider .ss-save-settings').data('id')),
				name : content.find('#ss-slider-name').val(),
				alias : content.find('#ss-slider-alias').text(),
				layout : content.find('#ss-slider-layout').val(),
				responsive : parseInt(content.find('#ss-slider-responsive').val()),
				startWidth : parseInt(content.find('#ss-slider-startWidth').val()),
				startHeight : parseInt(content.find('#ss-slider-startHeight').val()),
				automaticSlide : parseInt(content.find('#ss-slider-automaticSlide').val()),
				showControls : parseInt(content.find('#ss-slider-showControls').val()),
				showNavigation : parseInt(content.find('#ss-slider-showNavigation').val()),
				enableSwipe : parseInt(content.find('#ss-slider-enableSwipe').val()),
				showProgressBar : parseInt(content.find('#ss-slider-showProgressBar').val()),
				pauseOnHover : parseInt(content.find('#ss-slider-pauseOnHover').val()),
				//callbacks : content.find('#ss-slider-callbacks').val(),
			};
			
			// Do the ajax call
			jQuery.ajax({
				type : 'POST',
				dataType : 'json',
				url : ajaxurl,
				data : {
					// Is it saving or updating?
					action: $('.ss-admin .ss-slider').hasClass('ss-add-slider') ? 'surfaceslider_addSlider' : 'surfaceslider_editSlider',
					datas : options,
				},
				success: function(response) {
					//alert('Save slider response: ' + response);
					// If adding a new slider, response will be the generated id, else will be the number of rows modified
					if(response !== false) {
						// If is adding a slider, redirect
						if($('.ss-admin .ss-slider').hasClass('ss-add-slider')) {
							window.location.href = '?page=surfaceslider&view=edit&id=' + response;
							return;
						}
						
						surfaceslider_saveSlides();
					}
					else {
						surfaceslider_showError();
					}
				},
				
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					alert('Error saving slider');
					alert("Status: " + textStatus);
					alert("Error: " + errorThrown); 
					surfaceslider_showError();
				}
			});
		}
		
		// Sends an array with all the slides options
		function surfaceslider_saveSlides() {
			var slides = $('.ss-admin .ss-slider #ss-slides .ss-slide');
			var i = 0;
			var final_options = {};
			
			final_options['options'] = new Array();			
			slides.each(function() {
				var slide = $(this);
				var content = slide.find('.ss-slide-settings-list');
				
				var options = {					
					position : i,
					
					background_type_image : slide.find('.ss-slide-editing-area').css('background-image') == 'none' ? 'none' : slide.find('.ss-slide-editing-area').data('background-image-src') + "",
					background_type_color : content.find('input[name="ss-slide-background_type_color"]:checked').val() == '0' ? 'transparent' : slide.find('.ss-slide-editing-area').css('background-color') + "",
					background_propriety_position_x : content.find('.ss-slide-background_propriety_position_x').val(),
					background_propriety_position_y : content.find('.ss-slide-background_propriety_position_y').val(),
					background_repeat : content.find('input[name="ss-slide-background_repeat"]:checked').val() == '0' ? 'no-repeat' : 'repeat',
					background_propriety_size : content.find('.ss-slide-background_propriety_size').val(),
					data_in : content.find('.ss-slide-data_in').val(),
					data_out : content.find('.ss-slide-data_out').val(),
					data_time : parseInt(content.find('.ss-slide-data_time').val()),
					data_easeIn : parseInt(content.find('.ss-slide-data_easeIn').val()),
					data_easeOut : parseInt(content.find('.ss-slide-data_easeOut').val()),
					custom_css : content.find('.ss-slide-custom_css').val(),
				};
				
				final_options['options'][i] = options;
				
				i++;
			});
			
			final_options['slider_parent'] = parseInt($('.ss-admin .ss-save-settings').data('id')),
			
			// Do the ajax call
			jQuery.ajax({
				type : 'POST',
				dataType : 'json',
				url : ajaxurl,
				data : {
					action: 'surfaceslider_editSlides',
					datas : final_options,
				},
				success: function(response) {
					//alert('Save slides response: ' + response);
					if(response !== false) {
						surfaceslider_saveElements();
					}
					else {
						surfaceslider_showError();
					}
				},
				
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					alert('Error saving slides');
					alert("Status: " + textStatus);
					alert("Error: " + errorThrown); 
					surfaceslider_showError();
				}
			});
		}
		
		// Sends an array with all the elements options of each slide
		function surfaceslider_saveElements() {
			var slides = $('.ss-admin .ss-slider #ss-slides .ss-slide');
			var i = 0, j = 0;
			var final_options = {};
			
			final_options['options'] = new Array();
			slides.each(function() {
				var slide = $(this);
				var elements = slide.find('.ss-elements .ss-element-settings');
				
				elements.each(function() {
					var element = $(this);
					
					// Stop each loop when reach the void element
					if(element.hasClass('ss-void-element-settings')) {
						return;
					}
					
					var options = {
						slide_parent : i,	
						position : element.index(),
						type : element.hasClass('ss-text-element-settings') ? 'text' : element.hasClass('ss-image-element-settings') ? 'image' : '',
						
						inner_html : element.hasClass('ss-text-element-settings') ? element.find('.ss-element-inner_html').val() : '',
						image_src : element.hasClass('ss-image-element-settings') ? element.find('.ss-image-element-upload-button').data('src') : '',
						image_alt : element.hasClass('ss-image-element-settings') ? element.find('.ss-image-element-upload-button').data('alt') : '',
						data_left : parseInt(element.find('.ss-element-data_left').val()),
						data_top : parseInt(element.find('.ss-element-data_top').val()),
						z_index : parseInt(element.find('.ss-element-z_index').val()),
						data_delay : parseInt(element.find('.ss-element-data_delay').val()),
						data_time : element.find('.ss-element-data_time').val(),
						data_in : element.find('.ss-element-data_in').val(),
						data_out : element.find('.ss-element-data_out').val(),
						data_ignoreEaseOut : element.find('.ss-element-data_ignoreEaseOut').prop('checked') ? 1 : 0,
						data_easeIn : parseInt(element.find('.ss-element-data_easeIn').val()),
						data_easeOut : parseInt(element.find('.ss-element-data_easeOut').val()),
						custom_css : element.find('.ss-element-custom_css').val(),
						link : element.find('.ss-element-link').val(),
						link_new_tab : element.find('.ss-element-link_new_tab').prop('checked') ? 1 : 0,
					};
					
					final_options['options'][j] = options;
					
					j++;
				});
				
				i++;
			});
			
			// Proceed?
			final_options['elements'] = 1;
			if(final_options['options'].length == 0) {
				final_options['elements'] = 0;
			}
			
			final_options['slider_parent'] = parseInt($('.ss-admin .ss-save-settings').data('id'));
			
			// Do the ajax call
			jQuery.ajax({
				type : 'POST',
				dataType : 'json',
				url : ajaxurl,
				data : {
					action: 'surfaceslider_editElements',
					datas : final_options,
				},
				success: function(response) {
					//alert('Save elements response: ' + response);
					if(response !== false) {
						surfaceslider_showSuccess();
					}
					else {
						surfaceslider_showError();
					}
				},
				
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					alert('Error saving elements');
					alert("Status: " + textStatus);
					alert("Error: " + errorThrown); 
					surfaceslider_showError();
				}
			});
		}
		
		function surfaceslider_deleteSlider(content) {
			// Get options
			var options = {
				id : parseInt(content.data('delete')),
			};
			
			// Do the ajax call
			jQuery.ajax({
				type : 'POST',
				dataType : 'json',
				url : ajaxurl,
				data : {
					action: 'surfaceslider_deleteSlider',
					datas : options,
				},
				success: function(response) {
					//alert('Delete slider response: ' + response);
					if(response !== false) {
						content.parent().parent().remove();
						surfaceslider_showSuccess();
					}
					else {
						surfaceslider_showError();
					}
				},
				
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					alert('Error deleting slider');
					alert("Status: " + textStatus);
					alert("Error: " + errorThrown); 
					surfaceslider_showError();
				},
			});
		}

	});
})(jQuery);