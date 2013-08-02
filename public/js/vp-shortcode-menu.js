(function($){
	$(document).ready(function(){
		if(!jQuery.fn.insertAtCaret)
		{
			jQuery.fn.insertAtCaret = function(text) {
				return this.each(function() {
					if (document.selection && this.tagName == 'TEXTAREA') {
						//IE textarea support
						this.focus();
						sel = document.selection.createRange();
						sel.text = text;
						this.focus();
					} else if (this.selectionStart || this.selectionStart == '0') {
						//MOZILLA/NETSCAPE support
						startPos = this.selectionStart;
						endPos = this.selectionEnd;
						scrollTop = this.scrollTop;
						this.value = this.value.substring(0, startPos) + text + this.value.substring(endPos, this.value.length);
						this.focus();
						this.selectionStart = startPos + text.length;
						this.selectionEnd = startPos + text.length;
						this.scrollTop = scrollTop;
					} else {
						// IE input[type=text] and other browsers
						this.value += text;
						this.focus();
						this.value = this.value;
					}
				});
			};
		}

		jQuery.fn.scReset = function(){
			if( $(this).is('form') )
				$(this)[0].reset();
			
			$(this).find('.vp-sc-field').each(function(i){
				var type = $(this).attr('data-vp-type');
				switch (type)
				{
					case 'vp-upload':
						$(this).find('.image > img').attr('src', '');
						break;
					case 'vp-color':
						$(this).find('.vp-js-colorpicker').colorpicker('update', '');
						break;
					case 'vp-slider':
						var val     = $(this).find('input').val();
						var $slider = $(this).find('.vp-js-slider');
						if(!val)
						{
							val = $(this).find('.vp-js-slider').slider('option', 'min');
						}
						$slider.slider('value', val);
						break;
					case 'vp-textarea':
						$(this).find('textarea').val($(this).find('textarea').text());
						break;
					case 'vp-checkimage':
						// trigger change since form reset doesn't trigger it
						$(this).find('input').change();
						break;
					case 'vp-radioimage':
						// trigger change since form reset doesn't trigger it
						$(this).find('input').change();
						break;
					case 'vp-codeeditor':
						$(this).find('textarea').first().val($(this).find('textarea').text()).change();
						break;
				}
			});

			if ($.fn.select2)
			{
				// re-init select2
				$(this).find('.vp-js-select2').select2("destroy");
				// re-init select2 sortable
				if ($.fn.select2Sortable) $(this).find('.vp-js-sorter').select2("destroy");
				// re-init select2 fontawesome
				$(this).find('.vp-js-fontawesome').select2("destroy");

				vp.init_fontawesome_chooser($(this).find('.vp-js-fontawesome'));
				vp.init_select2($(this).find('.vp-js-select2'));
				vp.init_sorter($(this).find('.vp-js-sorter'));
			}
			return $(this);
		};

		// init controls
		vp.init_controls($('.vp-sc-menu-content'));

		// shortcode image controls event bind
		vp.custom_check_radio_event(".vp-sc-dialog", ".vp-checkimage .field .input label");
		vp.custom_check_radio_event(".vp-sc-dialog", ".vp-radioimage .field .input label");

		$('.vp-sc-menu li a').on('click', function(ev){
			ev.preventDefault();

			var $modal   = $(this).parents('.vp-sc-dialog'),
			    $parent  = $(this).parent('li'),
			    targetId = $(this).attr('href').substring(1),
			    $target  = $modal.find('.vp-sc-menu-content-' + targetId);

			// set clicked menu item as current
			$parent.siblings().removeClass('current');
			$parent.addClass('current');

			// show menu content
			$target.siblings().addClass('vp-hide');
			$target.removeClass('vp-hide');

			// stop event propagation
			return false;
		});

		$('.vp-sc-menu-item > a').bind('click.vp_sc', function(e){
			e.preventDefault();

			var $parent = $(this).parent('li'),
			    id      = $parent.attr('id'),
			    $form   = $parent.find('.vp-sc-form').first();

			if($parent.hasClass('active'))
			{
				$form.vp_slideUp();
				$form.scReset();
				$parent.removeClass('active');
			}
			else
			{
				var code   = $parent.find('.vp-sc-code').first().html(),
				    $modal = $(this).parents('.vp-sc-dialog').first();

				$modal.find('.vp-sc-menu-item').removeClass('active');
				$modal.find('.vp-sc-form').vp_slideUp();

				if($form.exists())
				{
					$parent.addClass('active');
					$form.vp_slideDown();
				}
				else
				{
					$modal.trigger('vp_insert_shortcode', code);
					$modal.trigger('reveal:close');
				}
			}
		});

		$('.vp-sc-insert').bind('click.vp_sc_insert', function(e){
			e.preventDefault();
			var $modal  = $(this).parents('.vp-sc-dialog'),
			    $parent = $(this).parents('.vp-sc-menu-item'),
			    $form   = $(this).parents('.vp-sc-form'),
			    $fields = $form.find('.vp-sc-field'),
			    values  = {},
			    code    = $parent.find('.vp-sc-code').first().html(),
			    atts    = '';

			// trigger non reloading form submit, so that any event binded on this called
			$form.on('submit', function(e){vp.tinyMCE_save(); e.preventDefault();});
			$form.submit();

			// gather unique names of the options
			$fields.each(function(i){
				var $input = $(this).find(':not(div).vp-input'),
				    name   = $input.attr('name'),
				    val    = $input.validationVal(),
				    type   = $(this).attr('data-vp-type');

				if(type === 'vp-toggle')
				{
					if(val) val = 'true';
					else val = 'false';
				}

				if(val && val !== '')
				{
					values[name.substring(1)] = val;
				}
			});

			for (var name in values)
			{
				if(values.hasOwnProperty(name))
				{
					atts += (" " + name + '="' + values[name] + '"');
				}
			}

			// print shortcode to editor					
			code = code.replace(']', atts + ']');
			$modal.trigger('vp_insert_shortcode', code);

			// reset form and close dialog
			$('.vp-sc-menu-item').removeClass('active');
			$form.vp_slideUp();
			$form.scReset();
			$modal.trigger('reveal:close');
		});

		$('.vp-sc-cancel').bind('click.vp_sc_cancel', function(e){
			e.preventDefault();
			$('.vp-sc-menu-item').removeClass('active');
			var $form   = $(this).parents('.vp-sc-form')
			$form.vp_slideUp();
			$form.scReset();
		});

	});
})(jQuery);