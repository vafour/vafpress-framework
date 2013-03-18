;(function($) {
	// jQuery hacks
	var _addClass = $.fn.addClass;
	$.fn.addClass = function() {
		var result = _addClass.apply( this, arguments );
		if (this.prop('tagName') == 'BODY' && arguments[0] == 'folded') { calculatePositionAndSize(); }
		return result;
	};
	var _removeClass = $.fn.removeClass;
	$.fn.removeClass = function() {
		var result = _removeClass.apply( this, arguments );
		if (this.prop('tagName') == 'BODY' && arguments[0] == 'folded') { calculatePositionAndSize(); }
		return result;
	};

	/* BEGIN FETCHING ALL FIELDS' VALIDATION and BINDING RULES */
	var validation   = [];
	var bindings     = [];
	var dependencies = [];
	var dep;
	$('.vp-menu-goto').each(function(i) {
		var href = $(this).attr('href'),
		    $panel = $(href),
		    fields = [];


		$panel.children('.vp-section').each(function(i) {
			var $section = $(this);

			dep = $section.attr('data-vp-dependency');
			dep && dependencies.push({dep: dep, type: 'section', source: $section.attr('id')});

			$section.find('.vp-field').each(function(j) {
				var $field = $(this),
					name   = $field.attr('id'),
					rules  = $field.attr('data-vp-validation'),
					bind   = $field.attr('data-vp-bind'),
					type   = $field.getDatas().type,
					$input = $('[name="' + name + '"]');

				dep = $field.attr('data-vp-dependency');

				dep   && dependencies.push({dep: dep, type: 'field', source: $field.attr('id')});
				bind  && bindings.push({bind: bind, type: type, source: name});
				rules && fields.push({name: name, rules: rules, type: type});
			});
		});

		if (fields.length > 0) validation.push({ name: href.trim('#'), fields: fields });
	});
	/* END FETCHING ALL FIELDS' VALIDATION and BINDING RULES */

	// get and click current hash
	$('.vp-js-menu-goto').click(function(e) {
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

		// Init Chosen
		if ($.fn.chosen) $panel.find('.vp-js-chosen').chosen();

		// Init Chosen Sortable
		if ($.fn.chosenSortable) $panel.find('.vp-js-sorter').addClass('chzn-sortable').chosen().chosenSortable();
	});

	// goto current menu
	var hash = window.location.hash;
	if (hash !== '')
	{
		$('a[href="' + hash + '"]').trigger('click');
	}
	else
	{
		$('.vp-current > .vp-js-menu-goto').click();
	}

	$('.vp-js-menu-dropdown').click(function(e) {
		e.preventDefault();
		var $this = $(this),
		    $parent = $this.parent('li'),
		    $li = $parent.siblings('li'),
		    $sub = $this.next('ul');
		if ($parent.hasClass('vp-current')) return;
		$li.removeClass('vp-current');
		$parent.addClass('vp-current');
		$sub.children('li.vp-current').children('a').click();
	});

	// Bindings
	for (var i = 0; i < bindings.length; i++)
	{
		var field = bindings[i],
		    temp  = field.bind.split('|'),
		    func  = temp[0],
		    dest  = temp[1],
		    ids   = [];

		dest = dest.split(',');

		for (var j = 0; j < dest.length; j++)
		{
			ids.push(dest[j]);
		}

		for (var j = 0; j < ids.length; j++)
		{
			vp.binding_event(ids, j, field, func, '.vp-wrap', 'option');
		}
	}
	/* ============================================================ */

	// DEPENDENCY
	for (var i = 0; i < dependencies.length; i++)
	{
		var field = dependencies[i],
		    temp  = field.dep.split('|'),
		    func  = temp[0],
		    dest  = temp[1],
		    ids   = [];

		dest = dest.split(',');

		for (var j = 0; j < dest.length; j++)
		{
			ids.push(dest[j]);
		}

		for (var j = 0; j < ids.length; j++)
		{
			vp.dependency_event(ids, j, field, func, '.vp-wrap');
		}
	}

	// Ajax Saving
	$('.vp-js-option-form').bind('submit', function(e) {
		e.preventDefault();

		$('.vp-js-option-form .vp-field').removeClass('vp-error');
		$('.validation-notif.vp-error').remove();
		$('.validation-msg.vp-error').remove();

		var msgHTML = '<li class="validation-msg vp-error"></li>',
		    menuNotifHTML = '<em class="validation-notif vp-error"></em>',
		    allError = 0;

		for (var i=0; i<validation.length; i++)
		{
			var panel = validation[i];

			panel.nError = 0;
			for (var j=0; j<panel.fields.length; j++)
			{
				var field = panel.fields[j],
				    $tr = $('#' + field.name),
				    $msgs = $tr.children('div.field').children('.validation-msgs').children('ul'),
				    $input = $('[name="' + field.name + '"]'),
				    val = $input.validationVal(),
				    type = field.type,
				    rules = field.rules.split('|');

				field.nError = 0;
				for (var k=0; k<rules.length; k++)
				{
					var rule = rules[k],
					    q1 = rule.indexOf('['),
					    q2 = rule.indexOf(']'),
					    def = (q1 >= 0) ? rule.substring(0, q1) : rule,
					    res = '',
					    n;

					switch (def)
					{
						case 'alphabet':
							if (!vp.validateAlphabet(type, val)) { res = vp_wp.val_msg.alphabet.format(); }
							break;
						case 'alphanumeric':
							if (!vp.validateAlphaNumeric(type, val)) { res = vp_wp.val_msg.alphanumeric.format(); }
							break;
						case 'numeric':
							if (!vp.validateNumeric(type, val)) { res = vp_wp.val_msg.numeric.format(); }
							break;
						case 'email':
							if (!vp.validateEmail(type, val)) { res = vp_wp.val_msg.email.format(); }
							break;
						case 'url':
							if (!vp.validateURL(type, val)) { res = vp_wp.val_msg.url.format(); }
							break;
						case 'maxlength':
							n = rule.substring(q1 + 1, q2);
							if (!vp.validateMaxLength(type, val, n)) { res = vp_wp.val_msg.maxlength.format(n); }
							break;
						case 'minlength':
							n = rule.substring(q1 + 1, q2);
							if (!vp.validateMinLength(type, val, n)) { res= vp_wp.val_msg.minlength.format(n); }
							break;
						case 'maxselected':
							n = rule.substring(q1 + 1, q2);
							if (!vp.validateMaxLength(type, val, n)) { res = vp_wp.val_msg.maxselected.format(n); }
							break;
						case 'minselected':
							n = rule.substring(q1 + 1, q2);
							if (!vp.validateMinLength(type, val, n)) { res= vp_wp.val_msg.minselected.format(n); }
							break;
						case 'required':
							if (!vp.validateRequired(type, val)) { res = vp_wp.val_msg.required.format(); }
							break;
					}

					if (res !== '')
					{
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

				if (field.nError > 0)
				{
					$tr.addClass('vp-error');
				}
			}
			if (panel.nError > 0)
			{
				// notify the menu which has the href
				var $notif = $(menuNotifHTML),
				    $anchor = $('[href="' + panel.name +'"]'),
				    $grandparent = $anchor.parent('li').parent('ul');
				$notif.appendTo($anchor);
				if ($grandparent.hasClass('vp-menu-level-2'))
				{
					if ($grandparent.siblings('a').children('.validation-notif.vp-error').length === 0)
					{
						$notif.clone().appendTo($grandparent.siblings('a'));
					}
				}
			}
		}

		// do not saving it any error occurs
		if (allError > 0) { return; }

		// otherwise, do saving
		var $loading = $('.vp-js-save-loader'),
			$button = $(this),
			$save_status = $('.vp-js-save-status'),
			$form = $('#vp-option-form'),
			option = $form.serializeArray(),
			data = {
				action: 'vp_ajax_save',
				option: option,
				nonce : vp_wp.nonce
			};

		$button.attr('disabled', 'disabled');
		$loading.stop(true, true).fadeIn(100);

		$.post(ajaxurl, data, function(response) {
			$save_status.html(response.message);
			if (response.status)
			{
				$save_status.addClass('success');
			}
			else
			{
				$save_status.addClass('failed');
			}
			$loading.stop(true, true).fadeOut(100, function() {
				$save_status.stop(true, true).fadeIn(100);
			});

			setTimeout(function() {
				$button.removeAttr('disabled');
				$save_status.stop(true, true).fadeOut(1000, function() {
					$save_status.removeClass('success').removeClass('failed');
				});
			}, 3000);
		}, 'JSON');

	});

	$('#vp-js-import').bind('click', function(e) {
		e.preventDefault();

		var $textarea      = $('#vp-js-import_text'),
		    $import_status = $('#vp-js-import-status'),
		    $import_loader = $('#vp-js-import-loader'),
		    $button        = $(this);
		    data           = {action: 'vp_ajax_import_option', option: $textarea.val(), nonce : vp_wp.nonce};

		$button.attr('disabled', 'disabled');
		$import_loader.fadeIn(100);

		$.post(ajaxurl, data, function(response) {
			$import_loader.fadeOut(0);
			if (response.status)
			{
				$import_status.html(vp_wp.impexp_msg.import_success);
			}
			else
			{
				$import_status.html(vp_wp.impexp_msg.import_failed + ': ' + response.message);
			}
			$import_status.fadeIn(100);
			setTimeout(function() {
				$import_status.fadeOut(1000, function() {
					$button.removeAttr('disabled');
					$import_status.fadeOut(500);
					if (response.status)
						location.reload();
				});
			}, 2000);
		}, 'JSON');
	});

	$('#vp-js-export').bind('click', function(e) {
		e.preventDefault();

		var $export_status = $('#vp-js-export-status'),
		    $export_loader = $('#vp-js-export-loader'),
		    $button        = $(this);
		    data           = {action: 'vp_ajax_export_option', nonce : vp_wp.nonce},

		$button.attr('disabled', 'disabled');
		$export_loader.fadeIn(100);
		$.post(ajaxurl, data, function(response) {
			$export_loader.fadeOut(0);
			if (!$.isEmptyObject(response.option) && response.status)
			{
				$('#vp-js-export_text').val(response.option);
				$export_status.html(vp_wp.impexp_msg.export_success);
			}
			else
			{
				$export_status.html(vp_wp.impexp_msg.export_failed + ': ' + response.message);
			}
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