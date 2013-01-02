;(function($) {

	/**
	 * =============================================================
	 * Simple Plugins
	 * =============================================================
	 */
	$.fn.getAttributes = function() {
		var attributes = {}; 
		if (!this.length)
			return this;
		$.each(this[0].attributes, function(index, attr) {
			attributes[attr.name] = attr.value;
		});
		return attributes;
	}
	$.fn.getDatas = function() {
		var attributes = {};
		var prefix = "data-vp-";
		if (!this.length)
			return this;
		$.each(this[0].attributes, function(index, attr) {
			if (attr.name.substring(0, prefix.length) == prefix)
			{
				attributes[attr.name.substring(prefix.length)] = attr.value;
			}
		});
		return attributes;
	}
	function isNumber(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
	if (!String.prototype.trimChar) {
		String.prototype.trimChar =  function(string) { return this.replace(new RegExp('^' + string + '+|' + string + '+$', 'g'), '') };
	}
	function parseOpt(optString)
	{
		var openIdx, closeIdx, temp, tempArr, opt = {};
		for (var i = 0; i < optString.length; i++)
		{
			if (optString[i] == '(')
			{
				openIdx = i;
			}
			if (optString[i] == ')')
			{
				closeIdx = i;
				temp = optString.substring(openIdx + 1, closeIdx);
				tempArr = temp.split(':');
				opt[tempArr[0]] = isNumber(tempArr[1]) ? parseFloat(tempArr[1]) : tempArr[1];
			}
		}
		return opt;
	}
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined'
				? args[number]
				: match
			;
		});
	};
	/*
	 * =============================================================
	 */

	if( jQuery().slider )
	{
		$('.vp-js-slider').each(function(i, el)
		{
			var $slider = $(this);
			var options = $(this).getDatas();
			options = parseOpt(options.opt);
			options.range = 'min';
			options.slide = function(event, ui) {
				$slider.prev('.slideinput').val(ui.value);
	    	};
			$slider.slider(options);

			$slider.prev('.slideinput').keypress(function(e) {
				var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
				console.log(charCode);
				// if ($.inArray(e.keyCode, [109, 9, 46, 189, 190, 8, 37, 39]) != -1 || (e.keyCode >= 48 && e.keyCode <= 57) )
				if(e.altKey || e.ctrlKey || e.shiftKey)
					return true;
				if ($.inArray(charCode, [45, 46, 8, 0]) != -1 || (charCode >= 48 && charCode <= 57) )
					return true;
				return false;
			}).blur(function(e) {
				var $this = $(this),
				    val = $this.val();
				if( !validateNumeric('vp-textbox', val) ){
					$this.val(options.min);
					$slider.slider('value', options.min);
				}
				if (val > options.max) {
					$this.val(options.max);
					$slider.slider('value', options.max);
					e.preventDefault();
				} else if (val < options.min) {
					$this.val(options.min);
					$slider.slider('value', options.min);
					e.preventDefault();
				} else {
					$slider.slider('value', $this.val());
				};
			});
		});
	}

	var upload_callback;

	if( vp.use_new_media_upload )
	{
		upload_callback = function(e) {

				var send_attachment = wp.media.editor.send.attachment,
						send_link = wp.media.editor.send.link,
						$this = $(this),
						$input = $this.prev('input'),
						$preview = $this.next().find('img');

				// handler for attachment
				wp.media.editor.send.attachment = function(props, attachment) {
					$input.val(attachment.url);
					$preview.attr('src', attachment.url);
					wp.media.editor.send.attachment = send_attachment;
				}

				// handler for link
				window.send_to_editor = function(html) {
					if (html != '') {
						// targetting only link, since attachment is already handled separately
						var imgurl = $(html).attr('src');
						$input.val(imgurl);
						$preview.attr('src', imgurl);
					}			
				}

				wp.media.editor.open($this);
				return false;
			}
	}
	else
	{
		upload_callback = function(e) {
				$input     = $(this).prev('input');
				$preview   = $(this).next().find('img');
				tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
				window.send_to_editor = function(html) {
					if (html != '') {
						var imgurl = $(html).find('img').attr('src');
						if( typeof imgurl == 'undefined' )
						{
							imgurl = $(html).attr('src');
						}
						$input.val(imgurl);
						$preview.attr('src', imgurl);
					}
					tb_remove();
				}
				return false;
			};
	}

	$('.vp-js-upload').click(upload_callback);

	if(jQuery().ColorPicker) {
		$('.vp-js-colorpicker').each(function(){
			var colorpicker  = this;
			$(colorpicker).ColorPicker({
				color: $(colorpicker).attr('value'),
				onSubmit: function(hsb, hex, rgb, el) {
					$(el).val(hex);
					$(el).ColorPickerHide();
				},
				onBeforeShow: function () {
					$(colorpicker).ColorPickerSetColor(this.value);
				},
				onShow: function (cp) {
					$(cp).stop(true, true).fadeIn(500);
					return false;
				},
				onHide: function (cp) {
					$(cp).stop(true, true).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$(colorpicker).prev('label').css('background-color', '#' + hex);
					$(colorpicker).attr('value', '#' + hex);
				}
			}).bind('keyup', function(e){
				var val = this.value.trimChar('#');
				if(this.value != ('#' + val))
				{
					$(colorpicker).attr('value', '#' + val);
				}
				$(this).ColorPickerSetColor(val);
				$(this).prev('label').css('background-color', '#' + val);
			});
		});
	}

	// Date Picker
	if(jQuery().datepicker) {
		$('.vp-js-datepicker').each(function(){
			var options = $(this).getDatas();
			options = parseOpt(options.opt);
			$(this).datepicker(options);
			$(this).datepicker('setDate', options.value);
		});
	}

	// Tipsy
	if(jQuery().tipsy) {
		$('.vp-js-tipsy.description').each(function() { $(this).tipsy({ gravity : 'e' }); });
		$('.vp-js-tipsy.slideinput').each(function() { $(this).tipsy({ trigger : 'focus' }); });
		$('.vp-js-tipsy.image-item').each(function() { $(this).tipsy(); });
	}

	// Chosen
	if(jQuery().chosen) {
		$('.vp-js-chosen').chosen();
 	}

 	function validateAlphabet(type, val) {
		// ignore array
		if ($.isArray(val) || $.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }
		var regex = new RegExp(/^[A-Z]+$/i);
		return regex.test(val);
	}

	function validateAlphaNumeric(type, val) {
		// ignore array
		if ($.isArray(val) || $.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

		var regex = new RegExp(/^[A-Z0-9]+$/i);
		return regex.test(val);
	}

	function validateNumeric(type, val) {
		// ignore array
		if ($.isArray(val) || $.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

		var regex = new RegExp(/^[-+]?[0-9]*\.?[0-9]+$/);
		return regex.test(val);
	}

	function validateEmail(type, val) {
		// ignore array
		if ($.isArray(val) || $.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

		var regex = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
		return regex.test(val);
	}

	function validateURL(type, val) {
		// ignore array
		if ($.isArray(val) || $.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

		var regex = new RegExp(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i);
		return regex.test(val);
	}

	function validateMaxLength(type, val, n) {
		// ignore array
		if ($.inArray(type, ['vp-toggle', 'vp-radiobutton', 'vp-radioimage', 'vp-select']) != -1) { return true; }

		return (val.length <= n) ? true : false;
	}

	function validateMinLength(type, val, n) {
		// ignore array
		if ($.inArray(type, ['vp-toggle', 'vp-radiobutton', 'vp-radioimage', 'vp-select']) != -1) { return true; }

		return (val.length >= n) ? true : false;
	}

	function validateRequired(type, val) {
		// only check if it's empty array, if it's not, it will go true anyway..
		if($.isArray(val) && $.isEmptyObject(val)) return false;
		return (val) ? true : false;
	}

}(jQuery));