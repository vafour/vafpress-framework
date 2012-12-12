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

	/* BEGIN FETCHING ALL FIELDS' VALIDATION RULES */
	var validation = [];
	$('.vp-menu-goto').each(function(i) {
		var href = $(this).attr('href'),
		    $panel = $(href),
		    fields = [];

		$panel.children('.vp-section').each(function(i) {
			var $section = $(this);
			$section.find('tr').each(function(j) {
				var $field = $(this),
				    name = $field.attr('id'),
				    rules = $field.attr('data-vp-validation'),
				    type = $field.attr('class'),
						$input = $('[name="' + name + '"]');

				if (! rules) return;
				else fields.push({name: name, rules: rules, type: type});
			})
		})

		if (fields.length > 0) validation.push({name: href.trim('#'), fields: fields});
	})
	/* END FETCHING ALL FIELDS' VALIDATION RULES */

	// get and click current hash
	$('.vp-js-menu-goto').click(function(e)
	{
		e.preventDefault();
		window.location.hash = $(this).attr('href');
		var $this = $(this),
		    $parent = $this.parent('li'),
		    $li = $parent.siblings('li'),
		    $panel = $($this.attr('href'));
		$li.removeClass('vp-current');
		$parent.addClass('vp-current');
		$panel.siblings('.vp-panel').removeClass('vp-current');
		$panel.addClass('vp-current');
	});

	$('.vp-js-menu-dropdown').click(function(e)
	{
		e.preventDefault();
		var $this = $(this),
		    $parent = $this.parent('li'),
		    $li = $parent.siblings('li'),
		    $sub = $this.next('ul');
		if ($parent.hasClass('vp-current')) return;
		$li.removeClass('vp-current');
		$parent.addClass('vp-current');
		$sub.children('li.vp-current').children('a').click();
	})

	// goto current menu
	var hash = window.location.hash;
	$('a[href="' + hash + '"]').trigger('click');

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
			$this = $(this);
			var pressedKey = String.fromCharCode(e.keyCode);
			var realVal = $this.val() + pressedKey;
			if (!isNumber(pressedKey) && $.inArray(pressedKey, ['.', '-']) == -1 ) {
				e.preventDefault();
				return;
			};
			
			if (realVal > options.max) {
				$this.val(options.max);
				$slider.slider('value', options.max);
				e.preventDefault();
			} else if (realVal < options.min) {
				$this.val(options.min);
				$slider.slider('value', options.min);
				e.preventDefault();
			} else {
				$slider.slider('value', $this.val());
			};
		});
	});

	$('.vp-js-upload').click(function()
	{
		formfield = jQuery('#fwpPhoto').attr('name');
		input     = $(this).prev('input');
		preview   = $(this).next().find('img');
		tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html)
	{
		imgurl = jQuery('img', html).attr('src');
		input.val(imgurl);
		preview.attr('src', imgurl);
		tb_remove();
	}

	$('.vp-js-colorpicker').each(function()
	{
		var colorpicker  = this;
		var defaultColor = $(colorpicker).attr('value');
		$(this).ColorPicker({
			color: defaultColor,
			// flat: true, 
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
				$(colorpicker).attr('value','#' + hex);
			}
		});
	}); //end color picker

	// Date Picker
	$('.vp-js-datepicker').each(function()
	{
		var options = $(this).getDatas();
		options = parseOpt(options.opt);
		$(this).datepicker(options);
	});

	// Tipsy
	$('.vp-js-tipsy').tipsy();

	// Chosen
	$('.vp-js-chosen').chosen();

	// Scrollspy
	var $submit = $('.vp-submit');
	$submit.scrollspy({
		min: parseInt($submit.offset().top - 28),
		max: $(document).height(),
		onEnter: function(element, position) {
			$submit.addClass('floating');
		},
		onLeave: function(element, position) {
			$submit.removeClass('floating');
		}
	});
	$(window).scroll();

	// Ajax Saving
	$('.vp-js-save').bind('click', function(e) {
		e.preventDefault();

		$('.validation-notif.error').remove();
		$('.validation-msg.error').remove();

		var msgHTML = '<li class="validation-msg error"></li>',
        menuNotifHTML = '<span class="validation-notif error"></span>',
        allError = 0;

    for (var i=0; i<validation.length; i++) {
    	var panel = validation[i];

    	panel.nError = 0;
    	for (var j=0; j<panel.fields.length; j++) {
    		var field = panel.fields[j],
    		    $tr = $('#' + field.name),
				    $msgs = $tr.children('td.fields').children('.validation-msgs').children('ul'),
				    $input = $('[name="' + field.name + '"]'),
				    val = $input.validationVal(),
				    type = field.type,
				    rules = field.rules.split('|');

				field.nError = 0;
				for (k=0; k<rules.length; k++) {
					var rule = rules[k],
					    q1 = rule.indexOf('['),
					    q2 = rule.indexOf(']'),
					    def = (q1 >= 0) ? rule.substring(0, q1) : rule,
					    res = '';

					switch (def) {
						case 'alphabet':
							if (!validateAlphabet(type, val)) { res = vp.val_msg.alphabet.format(); }
							break;
						case 'alphanumeric':
							if (!validateAlphaNumeric(type, val)) { res = vp.val_msg.alphanumeric.format(); }
							break;
						case 'numeric':
							if (!validateNumeric(type, val)) { res = vp.val_msg.numeric.format(); }
							break;
						case 'email':
							if (!validateEmail(type, val)) { res = vp.val_msg.email.format(); }
							break;
						case 'url':
							if (!validateURL(type, val)) { res = vp.val_msg.url.format(); }
							break;
						case 'maxlength':
							var n = rule.substring(q1 + 1, q2);
							if (!validateMaxLength(type, val, n)) { res = vp.val_msg.maxlength.format(n); }
							break;
						case 'minlength':
							var n = rule.substring(q1 + 1, q2);
							if (!validateMinLength(type, val, n)) { res= vp.val_msg.minlength.format(n); }
							break;
						case 'maxselected':
							var n = rule.substring(q1 + 1, q2);
							if (!validateMaxLength(type, val, n)) { res = vp.val_msg.maxselected.format(n); }
							break;
						case 'minselected':
							var n = rule.substring(q1 + 1, q2);
							if (!validateMinLength(type, val, n)) { res= vp.val_msg.minselected.format(n); }
							break;
						case 'required':
							if (!validateRequired(type, val)) { res = vp.val_msg.required.format(); }
							break;
					}

					if (res != '') {
						// push into errors pool
						field.nError += 1;
						panel.nError += 1;
						allError += 1;

						// set message
				    var $msg = $(msgHTML);
						$msg.html(res);
						$msg.appendTo($msgs);
					}
				}

    	}

			if (panel.nError > 0) {
				// notify the menu which has the href
				var $notif = $(menuNotifHTML),
				    $anchor = $('[href="' + panel.name +'"]'),
				    $grandparent = $anchor.parent('li').parent('ul');
				$notif.appendTo($anchor);
				if ($grandparent.hasClass('vp-menu-level-2')) {
					if ($grandparent.siblings('a').children('.validation-notif.error').length == 0) {
						$notif.clone().appendTo($grandparent.siblings('a'));
					}
				}
			}
    }

		// do not saving it any error occurs
    if (allError > 0) { return; }

		// otherwise, do saving
		var $overlay = $('#vp-overlay'),
		    $button = $(this),
		    $save_status = $('.vp-js-save-status'),
		    $form = $('#vp-option-form'),
		    option = $form.serializeArray(),
		    data = {
					action: 'vp_ajax_admin',
					option: option
				};
		
		$button.attr('disabled', 'disabled');
		$overlay.stop(true, true).fadeIn(100, function() {
			$overlay.removeClass('stop');
		});

		console.log(data);
		$.post(ajaxurl, data, function(response)
		{
			$save_status.html(response.message);
			if (response.status) {
				$save_status.addClass('success');
			} else {
				$save_status.addClass('failed');
			};
			$save_status.stop(true, true).fadeIn(100);

			$overlay.stop(true, true).fadeOut(100, function() {
				$overlay.addClass('stop');
			});

			setTimeout(function() {
				$button.removeAttr('disabled');
				$save_status.stop(true, true).fadeOut(1000, function() {
					$save_status.removeClass('success').removeClass('failed');
				});
			}, 3000);
		}, 'JSON');
	});

	// Overlay
	$(window).resize(function() {
		var $overlay = $('#vp-overlay'),
		    $loading = $('#vp-loading'),
		    $panel = $('#vp-option-panel'),
		    $right = $('.vp-right-panel'),
		    $submit = $('#vp-submit'),
		    $copyright = $('#vp-copyright');
		$overlay.css('height', $panel.innerHeight());
		$overlay.css('width', $panel.innerWidth());
		$submit.css('width', $right.innerWidth());
		$copyright.css('width', $right.innerWidth());
		$loading.css('top', $(this).height() / 2);
		$loading.css('left', $panel.innerWidth() / 2 + $panel.offset().left);
	});
	$(window).load($(window).resize());

	// Validation Functions

	$.fn.validationVal = function() {
		var $this = this,
		    val = '',
		    tagName = this.prop('tagName');
		
		if (($this.length > 1 && $this.attr('type') != 'radio') || $this.attr('multiple')) { val = []; }

		$this.each(function(i) {
			var $field = $(this);

			switch (tagName) {
				case 'SELECT':
					if ($field.has('[multiple]')) {
						val = $field.val();
					} else {
						val = $field.val();
					}
					break;
				case 'INPUT':
					switch ($this.attr('type')) {
						case 'text':
							val = $field.val();
							break;
						case 'radio':
							var checked = $field.attr('checked');
							if(typeof checked !== 'undefined' && checked !== false)
								val = $field.val();
							break;
						case 'checkbox':
							var checked = $field.attr('checked');
							if ($this.length > 1) {
								if (typeof checked !== 'undefined' && checked !== false) { val.push($field.val()); } // multiple
							} else {
								val = $field.val(); // single
							}
							break;
					}
					break;
				case 'TEXTAREA':
					val = $field.val();
					break;
			}
		})
		return val;
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

		var regex = new RegExp(/^[0-9.]+$/);
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

	$('#vp-js-import').bind('click', function(e){
		e.preventDefault();

		var $textarea      = $('#vp-js-import_text'),
			$import_status = $('#vp-js-import-status'),
		    $import_loader = $('#vp-js-import-loader'),
		    $button        = $(this);
			data           = {action: 'vp_ajax_import_option', option: $textarea.val()};

		$button.attr('disabled', 'disabled');
		$import_loader.fadeIn(100);

		$.post(ajaxurl, data, function(response)
		{
			$import_loader.fadeOut(0);
			if (response.status) {
				$import_status.html(vp.impexp_msg.import_success);
			} else {
				$import_status.html(vp.impexp_msg.export_failed);
			};
			$import_status.fadeIn(100);
			setTimeout(function() {
				$import_status.fadeOut(1000, function() {
					$button.removeAttr('disabled');
					$import_status.fadeOut(500);
					location.reload();
				});
			}, 2000);
		}, 'JSON');
	});

	$('#vp-js-export').bind('click', function(e){
		e.preventDefault();

		var $export_status = $('#vp-js-export-status'),
		    $export_loader = $('#vp-js-export-loader'),
			$button        = $(this);
			data           = {action: 'vp_ajax_export_option'},

		$button.attr('disabled', 'disabled');
		$export_loader.fadeIn(100);
		$.post(ajaxurl, data, function(response)
		{
			$export_loader.fadeOut(0);
			if (!$.isEmptyObject(response)) {
				$('#vp-js-export_text').val(response.option);
				$export_status.html(vp.impexp_msg.export_success);
			} else {
				$export_status.html(vp.impexp_msg.export_failed);
			};
			$export_status.fadeIn(100);				
			setTimeout(function() {
				$export_status.fadeOut(1000, function() {
					$button.removeAttr('disabled');
					$export_status.fadeOut(500);
				});
			}, 3000);
		}, 'JSON');
	});

}(jQuery));