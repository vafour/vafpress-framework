/**
 * =============================================================
 * JQuery or Other Extension
 * =============================================================
 */
jQuery.fn.getAttributes = function() {
	var attributes = {}; 
	if (!this.length)
		return this;
	jQuery.each(this[0].attributes, function(index, attr) {
		attributes[attr.name] = attr.value;
	});
	return attributes;
}
jQuery.fn.getDatas = function() {
	var attributes = {},
	    prefix = "data-vp-";
	if (!this.length)
		return this;
	jQuery.each(this[0].attributes, function(index, attr) {
		if (attr.name.substring(0, prefix.length) == prefix)
		{
			attributes[attr.name.substring(prefix.length)] = attr.value;
		}
	});
	return attributes;
}
if (!String.prototype.trimChar) {
	String.prototype.trimChar =  function(string) { return this.replace(new RegExp('^' + string + '+|' + string + '+$', 'g'), '') };
}
if (!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined'
				? args[number]
				: match
			;
		});
	};
}
/*
 * =============================================================
 */


/**
 * =============================================================
 * Vafpress function
 * =============================================================
 */

/**
 * vafpress global namespace
 */
var vp = {};

vp.isNumber = function(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
vp.parseOpt = function(optString) {
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
			opt[tempArr[0]] = vp.isNumber(tempArr[1]) ? parseFloat(tempArr[1]) : tempArr[1];
		}
	}
	return opt;
}

vp.validateAlphabet = function(type, val) {
	// ignore array
	if (jQuery.isArray(val) || jQuery.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }
	var regex = new RegExp(/^[A-Z]+$/i);
	return regex.test(val);
}

vp.validateAlphaNumeric = function(type, val) {
	// ignore array
	if (jQuery.isArray(val) || jQuery.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

	var regex = new RegExp(/^[A-Z0-9]+$/i);
	return regex.test(val);
}

vp.validateNumeric = function(type, val) {
	// ignore array
	if (jQuery.isArray(val) || jQuery.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

	var regex = new RegExp(/^[-+]?[0-9]*\.?[0-9]+$/);
	return regex.test(val);
}

vp.validateEmail = function(type, val) {
	// ignore array
	if (jQuery.isArray(val) || jQuery.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

	var regex = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
	return regex.test(val);
}

vp.validateURL = function(type, val) {
	// ignore array
	if (jQuery.isArray(val) || jQuery.inArray(type, ['vp-textbox', 'vp-textarea']) == -1) { return true; }

	var regex = new RegExp(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i);
	return regex.test(val);
}

vp.validateMaxLength = function(type, val, n) {
	// ignore array
	if (jQuery.inArray(type, ['vp-toggle', 'vp-radiobutton', 'vp-radioimage', 'vp-select']) != -1) { return true; }

	return (val.length <= n) ? true : false;
}

vp.validateMinLength = function(type, val, n) {
	// ignore array
	if (jQuery.inArray(type, ['vp-toggle', 'vp-radiobutton', 'vp-radioimage', 'vp-select']) != -1) { return true; }

	return (val.length >= n) ? true : false;
}

vp.validateRequired = function(type, val) {
	// only check if it's empty array, if it's not, it will go true anyway..
	if (jQuery.isArray(val) && jQuery.isEmptyObject(val)) return false;
	return (val) ? true : false;
}
/*
 * =============================================================
 */



/**
 * =============================================================
 * Control Fields JS Trigering
 * =============================================================
 */

if (jQuery.fn.slider)
{
	jQuery('.vp-js-slider').each(function(i, el) {
		var $slider = jQuery(this),
		    options = jQuery(this).getDatas();
		options = vp.parseOpt(options.opt);
		options.range = 'min';
		options.slide = function(event, ui) {
			$slider.prev('.slideinput').val(ui.value);
		};
		$slider.slider(options);

		$slider.prev('.slideinput').keypress(function(e) {
			var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
			if (e.altKey || e.ctrlKey || e.shiftKey)
				return true;
			if (jQuery.inArray(charCode, [45, 46, 8, 0]) != -1 || (charCode >= 48 && charCode <= 57) )
				return true;
			return false;
		}).blur(function(e) {
			var $this = jQuery(this),
			    val = $this.val();
			if (!vp.validateNumeric('vp-textbox', val))
			{
				$this.val(options.min);
				$slider.slider('value', options.min);
			}
			if (val > options.max)
			{
				$this.val(options.max);
				$slider.slider('value', options.max);
				e.preventDefault();
			}
			else if (val < options.min)
			{
				$this.val(options.min);
				$slider.slider('value', options.min);
				e.preventDefault();
			}
			else
			{
				$slider.slider('value', $this.val());
			};
		});
	});
}


vp.upload_callback = function() {};

if ( vp_wp.use_new_media_upload )
{
	var _orig_send_attachment = wp.media.editor.send.attachment,
	    _orig_send_link       = wp.media.editor.send.link,
	    _orig_send_to_editor  = window.send_to_editor;

	vp.upload_callback = function(e) {
		var $this = jQuery(this),
		    $input = $this.prev('input'),
		    $preview = $this.parent().next().find('img');

		// handler for attachment
		wp.media.editor.send.attachment = function(props, attachment) {
			$input.val(attachment.url);
			$preview.attr('src', attachment.url);
			wp.media.editor.send.attachment = _orig_send_attachment;
			window.send_to_editor = _orig_send_to_editor;
		}

		// handler for link
		window.send_to_editor = function(html) {
			if (html != '')
			{
				// targetting only link, since attachment is already handled separately
				var imgurl = jQuery(html).attr('src');
				$input.val(imgurl);
				$preview.attr('src', imgurl);
			}
			window.send_to_editor = _orig_send_to_editor;
			wp.media.editor.send.attachment = _orig_send_attachment;
		}
		wp.media.editor.open($this);
		return false;
	}
}
else
{
	var _orig_send_to_editor  = window.send_to_editor;

	vp.upload_callback = function(e) {
		var _custom_media = true;
		$input     = jQuery(this).prev('input');
		$preview   = jQuery(this).parent().next().find('img');
		tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');

		window.send_to_editor = function(html) {
			if (html != '')
			{
				var imgurl = jQuery(html).find('img').attr('src');
				if (typeof imgurl == 'undefined')
				{
					imgurl = jQuery(html).attr('src');
				}
				$input.val(imgurl);
				$preview.attr('src', imgurl);
			}
			window.send_to_editor = _orig_send_to_editor;
			tb_remove();
		}
		return false;
	};
}

jQuery('.vp-js-upload').click(vp.upload_callback);

if (jQuery.fn.ColorPicker)
{
	jQuery('.vp-js-colorpicker').each(function() {
		var colorpicker  = this;
		jQuery(colorpicker).ColorPicker({
			color: jQuery(colorpicker).attr('value'),
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow: function() {
				jQuery(colorpicker).ColorPickerSetColor(this.value);
			},
			onShow: function(cp) {
				jQuery(cp).stop(true, true).fadeIn(500);
				return false;
			},
			onHide: function(cp) {
				jQuery(cp).stop(true, true).fadeOut(500);
				return false;
			},
			onChange: function(hsb, hex, rgb) {
				jQuery(colorpicker).prev('label').css('background-color', '#' + hex);
				jQuery(colorpicker).attr('value', '#' + hex);
			}
		}).bind('keyup', function(e) {
			var val = this.value.trimChar('#');
			if (this.value != ('#' + val))
			{
				jQuery(colorpicker).attr('value', '#' + val);
			}
			jQuery(this).ColorPickerSetColor(val);
			jQuery(this).prev('label').css('background-color', '#' + val);
		});
	});
}

// Date Picker
if (jQuery.fn.datepicker)
{
	jQuery('.vp-js-datepicker').each(function() {
		var options = jQuery(this).getDatas();
		options     = vp.parseOpt(options.opt);
		jQuery(this).datepicker(options);
		jQuery(this).datepicker('setDate', options.value);
	});
}

// Tipsy
if (jQuery.fn.tipsy)
{
	jQuery('.vp-js-tipsy.description').each(function() { jQuery(this).tipsy({ gravity : 'e' }); });
	jQuery('.vp-js-tipsy.slideinput').each(function() { jQuery(this).tipsy({ trigger : 'focus' }); });
	jQuery('.vp-js-tipsy.image-item').each(function() { jQuery(this).tipsy(); });
}

/*
 * =============================================================
 */