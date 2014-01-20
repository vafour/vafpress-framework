/* =============================================================
 * JQuery or Other Extension
 * =============================================================
 */

"use strict";

jQuery.fn.getAttributes = function() {
	var attributes = {};
	if (!this.length)
		return this;
	jQuery.each(this[0].attributes, function(index, attr) {
		attributes[attr.name] = attr.value;
	});
	return attributes;
};
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
};
jQuery.fn.exists = function () {
	return this.length !== 0;
};
if (!String.prototype.trimChar) {
	String.prototype.trimChar =  function(string) { return this.replace(new RegExp('^' + string + '+|' + string + '+$', 'g'), ''); };
}
if (!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}
if (!String.prototype.replaceAll){
	/**
	 * ReplaceAll by Fagner Brack (MIT Licensed)
	 * Replaces all occurrences of a substring in a string
	 */
	String.prototype.replaceAll = function(token, newToken, ignoreCase) {
		var str, i = -1, _token;
		if((str = this.toString()) && typeof token === "string") {
			_token = ignoreCase === true? token.toLowerCase() : undefined;
			while((i = (
				_token !== undefined?
					str.toLowerCase().indexOf(
								_token,
								i >= 0? i + newToken.length : 0
					) : str.indexOf(
								token,
								i >= 0? i + newToken.length : 0
					)
			)) !== -1 ) {
				str = str.substring(0, i)
						 .concat(newToken)
						 .concat(str.substring(i + token.length));
			}
	    }
	return str;
	};
}
// Validation Functions
jQuery.fn.validationVal = function() {
	var $this = this,
	    val = '',
	    tagName = $this.prop('tagName'),
	    checked;

	if (($this.length > 1 && $this.attr('type') != 'radio') || $this.attr('multiple')) { val = []; }

	var initialVal = val;

	$this.each(function(i) {
		var $field = jQuery(this);

		switch (tagName)
		{
			case 'SELECT':
				if ($field.has('[multiple]'))
				{
					val = $field.val();
				}
				else
				{
					val = $field.val();
				}
				break;
			case 'INPUT':
				switch ($this.attr('type'))
				{
					case 'text':
						val = $field.val();
						break;
					case 'radio':
						checked = $field.prop('checked');
						if (typeof checked !== 'undefined' && checked !== false)
							val = $field.val();
						break;
					case 'checkbox':
						checked = $field.prop('checked');
						if ($this.length > 1)
						{
							if (typeof checked !== 'undefined' && checked !== false) { val.push($field.val()); } // multiple
						}
						else
						{
							val = null;
							if (typeof checked !== 'undefined' && checked !== false) { val = $field.val(); } // multiple
						}
						break;
				}
				break;
			case 'TEXTAREA':
				val = $field.val();
				break;
			default:
				val = $field.val();
				break;
		}
	});

	// quick fix trial
	if (val === null)
		val = initialVal;
	return val;
};

// hidding plugin
jQuery.fn.vp_slideUp = function(callback){
	var $this = this;

	$this.each(function(i){
		var $el = jQuery(this);
		if(!$el.hasClass('vp-hide'))
		{
			$el.slideUp('fast', function() {
				jQuery(this).addClass('vp-hide')
				.slideDown(0, callback);
			});
		}
	});
};
jQuery.fn.vp_slideDown = function(callback){
	var $this = this;

	$this.each(function(i){
		var $el = jQuery(this);
		if($el.hasClass('vp-hide'))
		{
			$el.slideUp(0, function() {
				jQuery(this).removeClass('vp-hide')
				.slideDown('fast', callback);
			});
		}
	});
};
jQuery.fn.vp_fadeOut = function(callback){
	var $this = this;
	if(!$this.hasClass('vp-hide'))
	{
		$this.fadeOut('fast', function() {
			jQuery(this).addClass('vp-hide')
			.fadeIn(0, callback);
		});
	}
};
jQuery.fn.vp_fadeIn = function(callback){
	var $this = this;
	if($this.hasClass('vp-hide'))
	{
		$this.fadeOut(0,function() {
			jQuery(this).removeClass('vp-hide')
			.fadeIn('fast', callback);
		});
	}
};
jQuery.fn.vp_toggle = function(callback){
	var $this = this;
	if($this.hasClass('vp-hide'))
	{
		$this.slideUp(0,function() {
			jQuery(this).removeClass('vp-hide')
			.slideDown('fast', callback);
		});
	}
	else
	{
		$this.slideUp('fast', function() {
			jQuery(this).addClass('vp-hide')
			.slideDown(0, callback);
		});
	}
};

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
};
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
};

vp.wp_ext2type = function ( ext ) {
	var ext2type = {
		image       : ['jpg', 'jpeg', 'bmp',  'gif',  'png'],
		audio       : ['aac', 'ac3',  'aif',  'aiff', 'm3a',  'm4a',   'm4b',  'mka',  'mp1',  'mp2',  'mp3', 'ogg', 'oga', 'ram', 'wav', 'wma'],
		video       : ['asf', 'avi',  'divx', 'dv',   'flv',  'm4v',   'mkv',  'mov',  'mp4',  'mpeg', 'mpg', 'mpv', 'ogm', 'ogv', 'qt',  'rm', 'vob', 'wmv'],
		document    : ['doc', 'docx', 'docm', 'dotm', 'odt',  'pages', 'pdf',  'rtf',  'wp',   'wpd'],
		spreadsheet : ['numbers',     'ods',  'xls',  'xlsx', 'xlsm',  'xlsb'],
		interactive : ['swf', 'key',  'ppt',  'pptx', 'pptm', 'pps',   'ppsx', 'ppsm', 'sldx', 'sldm', 'odp'],
		text        : ['asc', 'csv',  'tsv',  'txt'],
		archive     : ['bz2', 'cab',  'dmg',  'gz',   'rar',  'sea',   'sit',  'sqx',  'tar',  'tgz',  'zip', '7z'],
		code        : ['css', 'htm',  'html', 'php',  'js']
	};

	var result = 'default';

	for(var type in ext2type)
	{
		if(ext2type.hasOwnProperty(type))
		{
			if(jQuery.inArray(ext, ext2type[type]) !== -1 )
			{
				result = type;
				break;
			}
		}
	}
	return result;
};

vp.get_url_extension = function(url){
	var regex    = new RegExp(/(.*)[\/\\]([^\/\\]+)\.(\w+)/i);
	var url_info = regex.exec(url);
	if(url_info)
	{
		if (typeof url_info[3] != 'undefined')
		{
			return url_info[3];
		}
	}
	return '';
};

vp.jqid = function(id) {
	return '#' + id.replace(/([:\.\[\]])/g,'\\$1');
};

vp.jqidwild = function(id) {
	id = id.replace(/([:\.\[\]])/g,'\\$1');
	id = '[id*=' + id + ']';
	return id;
};

vp.jqname = function(name) {
	return '[name="' + name + '"]';
};

vp.jqnamewild = function(name) {
	return '[name*="' + name + '"]';
};

vp.thejqid = function(id, thecase) {
	if(thecase === 'option')
		return vp.jqid(id);
	if(thecase === 'metabox')
		return vp.jqidwild(id);
	return id;
};

vp.thejqname = function(name, thecase) {
	if(thecase === 'option')
		return vp.jqname(name);
	if(thecase === 'metabox')
		return vp.jqnamewild(name);
	return name;
};

vp.validateAlphabet = function(type, val) {
	// ignore array and empty string, since they should be handled by 'required' rule
	if (val === '' || jQuery.isArray(val) || jQuery.inArray(type, vp_wp.alphabet_validatable) == -1) { return true; }
	var regex = new RegExp(/^[A-Z\s]+$/i);
	return regex.test(val);
};

vp.validateAlphaNumeric = function(type, val) {
	// ignore array and empty string, since they should be handled by 'required' rule
	if (val === '' || jQuery.isArray(val) || jQuery.inArray(type, vp_wp.alphanumeric_validatable) == -1) { return true; }

	var regex = new RegExp(/^[A-Z0-9]+$/i);
	return regex.test(val);
};

vp.validateNumeric = function(type, val) {
	// ignore array and empty string, since they should be handled by 'required' rule
	if (val === '' || jQuery.isArray(val) || jQuery.inArray(type, vp_wp.numeric_validatable) == -1) { return true; }

	var regex = new RegExp(/^[-+]?[0-9]*\.?[0-9]+$/);
	return regex.test(val);
};

vp.validateEmail = function(type, val) {
	// ignore array and empty string, since they should be handled by 'required' rule
	if (val === '' || jQuery.isArray(val) || jQuery.inArray(type, vp_wp.email_validatable) == -1) { return true; }

	var regex = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
	return regex.test(val);
};

vp.validateURL = function(type, val) {
	// ignore array and empty string, since they should be handled by 'required' rule
	if (val === '' || jQuery.isArray(val) || jQuery.inArray(type, vp_wp.url_validatable) == -1) { return true; }

	var regex = new RegExp(/^(ftp|http|https):\/\/[^ "]+$/i);
	return regex.test(val);
};

vp.validateMaxLength = function(type, val, n) {
	// ignore array
	if (jQuery.inArray(type, vp_wp.maxlength_validatable) != -1) { return true; }

	return (val.length <= n) ? true : false;
};

vp.validateMinLength = function(type, val, n) {
	// ignore array
	if (jQuery.inArray(type, vp_wp.minlength_validatable) != -1) { return true; }

	return (val.length >= n) ? true : false;
};

vp.validateRequired = function(type, val) {
	// only check if it's empty array, if it's not, it will go true anyway..
	if (jQuery.isArray(val) && jQuery.isEmptyObject(val)) return false;
	return (val) ? true : false;
};

// validation function loop
vp.fields_validation_loop = function(fields){

	var msgHTML = '<li class="validation-msg vp-error"></li>',
		errors  = 0;

	for (var i = 0; i < fields.length; i++)
	{
		var field   = fields[i],
		    $tr     = jQuery(vp.jqid(field.name)),
		    $parent = $tr.parents('.vp-meta-group').exists() ? $tr.parents('.vp-meta-group') : $tr.parents('.vp-section');

		// if field is now deleted and not found
		if($tr.length <= 0)
			continue;

		// if field is inactive due to dependencies mechanism
		if($tr.hasClass('vp-dep-inactive') || ($parent.exists() && $parent.hasClass('vp-dep-inactive')))
			continue;

		var $msgs  = $tr.children('div.field').children('.validation-msgs').children('ul'),
		    $input = jQuery('[name="' + field.name + '"]'),
		    val    = $input.validationVal(),
		    type   = field.type,
		    rules  = field.rules.split('|');

		field.nError = 0;
		for (var j = 0; j < rules.length; j++)
		{
			var rule = rules[j],
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

				// set message
				var $msg = jQuery(msgHTML);
				    $msg.html(res);
				    $msg.appendTo($msgs);
			}
		}

		if (field.nError > 0)
		{
			errors += 1;
			$tr.addClass('vp-error');
		}
	}
	return errors;
};

// custom checkbox and radiobutton handler
vp.custom_check_radio_event = function(parent, selector){
	jQuery(parent).delegate(selector, "click", function(e){
		e.preventDefault();
		var $control = jQuery(this).find('input'),
		    type = $control.attr('type');
		if(type == 'radio')
		{
			jQuery(this).parent().parent().find('input').each(function(i){
				jQuery(this).removeClass('checked');
				$control.prop('checked', false);
			});
			$control.prop('checked', true).change();
		}
		else if(type == 'checkbox')
		{
			if ($control.is(':checked'))
				$control.prop('checked', false);
			else
				$control.prop('checked', true);
			$control.trigger('change');
		}
	});
	jQuery(parent).delegate(selector, "change", function(e){
		e.preventDefault();
		var $control = jQuery(this).find('input');
		if ($control.is(':checked'))
			$control.addClass('checked');
		else
			$control.removeClass('checked');
	});
};

// vafpress binding related functions
vp.binding_action =	function(ids, field, func, thecase) {
	var $source_tr = jQuery(vp.jqid(field.source)),
	    $source    = jQuery('[name="' + field.source + '"]'),
	    values     = [];

	for (var i = 0; i < ids.length; i++)
	{
		var val = jQuery(vp.thejqname(ids[i], thecase)).validationVal();
		if (jQuery.isArray(val) && val.length == 0) {
			val = null;
		}
		values.push(val);
	}

	var data = {
		action   : 'vp_ajax_wrapper',
		func     : func,
		params   : values
	};

	// get loader
	var $loader = $source_tr.find('.vp-js-bind-loader'),
	    $input  = $source_tr.find('.input');

	if (field.type == 'vp-html') {
		$loader.vp_fadeIn();
	} else {
		$input.vp_fadeOut(function(){
			$loader.vp_fadeIn();
		});
	}

	jQuery.post(ajaxurl, data, function(response) {
		$loader.vp_fadeOut(function(){
			$input.vp_fadeIn();
		});
		if (response.status)
		{
			var data;
			switch(field.type)
			{
				case 'vp-select':
				case 'vp-multiselect':
					$source    = jQuery('[name="' + field.source + '"]');
					if(response.data !== null)
					{
						data = response.data instanceof Array ? response.data : [response.data];
						$source.val(data).change();
					}
					break;
				case 'vp-checkbox':
					if(response.data !== null)
					{
						data = response.data instanceof Array ? response.data : [response.data];
						$source.prop('checked', false).change();
						jQuery.each(data, function(key, value) {
							$source.filter('[value="'+ value +'"]').prop('checked', true).change();
						});
					}
					break;
				case 'vp-toggle':
					if(response.data !== null)
					{
						if(response.data)
							$source.prop('checked', true).change();
						else
							$source.prop('checked', false).change();
					}
					break;
				case 'vp-checkimage':
					if(response.data !== null)
					{
						data = response.data instanceof Array ? response.data : [response.data];
						$source.prop('checked', false).change();
						jQuery.each(data, function(key, value) {
							$source.filter('[value="'+ value +'"]').prop('checked', true).change();
						});
					}
					vp.init_tipsy();
					break;
				case 'vp-radiobutton':
					if(response.data !== null)
					{
						data = response.data instanceof Array ? response.data : [response.data];
						$source.prop('checked', false).change();
						jQuery.each(data, function(key, value) {
							$source.filter('[value="'+ value +'"]').prop('checked', true).change();
						});
					}
					break;
				case 'vp-radioimage':
					if(response.data !== null)
					{
						data = response.data instanceof Array ? response.data : [response.data];
						$source.prop('checked', false).change();
						jQuery.each(data, function(key, value) {
							$source.filter('[value="'+ value +'"]').prop('checked', true).change();
						});
					}
					vp.init_tipsy();
					break;
				case 'vp-html':
					if(response.data !== null)
					{
						jQuery(vp.jqid(field.source + '_dom')).html(response.data);
						jQuery(vp.jqname(field.source)).val(response.data);
					}
					break;
				default:
					$source = jQuery(vp.jqname(field.source));
					$source.val(response.data);
			}
			jQuery('[name="' + field.source + '"]:first').keypress().keyup().change().blur();
		}
	}, 'JSON');
};

vp.binding_event = function(ids, idx, field, func, parent, thecase)
{
	var change    = ['vp-select', 'vp-checkbox', 'vp-checkimage', 'vp-radiobutton', 'vp-radioimage', 'vp-multiselect', 'vp-toggle', 'vp-upload'],
	    typing    = ['vp-textbox', 'vp-slider', 'vp-color', 'vp-date'],
	    name      = vp.thejqname(ids[idx], thecase),
	    dest_type = jQuery(vp.thejqid(ids[idx], thecase)).attr('data-vp-type');

	if(jQuery.inArray(dest_type, change) !== -1 )
	{
		jQuery(parent).delegate(name, 'change', function(){vp.binding_action(ids, field, func, thecase);});
	}
	else if(jQuery.inArray(dest_type, typing) !== -1 )
	{
		jQuery(name).typing({
			stop: function(event, $elem){
				vp.binding_action(ids, field, func, thecase);
			},
			delay: 400
		});
	}
	else
	{
		jQuery(parent).delegate(name, 'change', function(){vp.binding_action(ids, field, func, thecase);});
	}
};

/*
 * =============================================================
 */

// vafpress binding related functions
vp.items_binding_action =	function(ids, field, func, thecase) {
	var $source_tr = jQuery(vp.jqid(field.source)),
	    $source    = jQuery('[name="' + field.source + '"]'),
	    values     = [];

	for (var i = 0; i < ids.length; i++)
	{
		values.push(jQuery(vp.thejqname(ids[i], thecase)).validationVal());
	}

	var data = {
		action   : 'vp_ajax_wrapper',
		func     : func,
		params   : values
	};

	// get loader
	var $loader = $source_tr.find('.vp-js-bind-loader'),
	    $input  = $source_tr.find('.input');

	$input.vp_fadeOut(function(){
		$loader.vp_fadeIn();
	});

	jQuery.post(ajaxurl, data, function(response) {
		$loader.vp_fadeOut(function(){
			$input.vp_fadeIn();
		});
		if (response.status)
		{
			var $source;
			switch(field.type)
			{
				case 'vp-select':
				case 'vp-multiselect':
					$source    = jQuery('[name="' + field.source + '"]');
					$source.empty();
					$source.append(jQuery("<option></option>"));
					response.data !== null && jQuery.each(response.data, function(key, value) {
						$source
							.append(jQuery("<option></option>")
							.attr("value",value.value)
							.text(value.label));
					});
					break;
				case 'vp-checkbox':
					$source = $input;
					$source.empty();
					$source = jQuery(vp.jqid(field.source)).find('.input');
					response.data !== null && jQuery.each(response.data, function(key, value) {
						$source.append(jQuery('<label><input class="vp-input" type="checkbox" name="' + field.source + '" value="' + value.value + '"><span></span>'+ value.label + '</label>'));
					});
					break;
				case 'vp-checkimage':
					$source = $input;
					$source.empty();
					$source = jQuery(vp.jqid(field.source)).find('.input');
					response.data !== null && jQuery.each(response.data, function(key, value) {
						$source.append(jQuery('<label><input class="vp-input" type="checkbox" name="' + field.source + '" value="' + value.value + '"><img src="' + value.img + '" alt="' + value.label + '" class="vp-js-tipsy image-item" style="" original-title="' + value.value + '"></label>'));
					});
					vp.init_tipsy();
					break;
				case 'vp-radiobutton':
					$source = $input;
					$source.empty();
					$source = jQuery(vp.jqid(field.source)).find('.input');
					response.data !== null && jQuery.each(response.data, function(key, value) {
						$source.append(jQuery('<label><input class="vp-input" type="radio" name="' + field.source + '" value="' + value.value + '"><span></span>'+ value.label + '</label>'));
					});
					break;
				case 'vp-radioimage':
					$source = $input;
					$source.empty();
					$source = jQuery(vp.jqid(field.source)).find('.input');
					response.data !== null && jQuery.each(response.data, function(key, value) {
						$source.append(jQuery('<label><input class="vp-input" type="radio" name="' + field.source + '" value="' + value.value + '"><img src="' + value.img + '" alt="' + value.label + '" class="vp-js-tipsy image-item" style="" original-title="' + value.value + '"></label>'));
					});
					vp.init_tipsy();
					break;
			}
			jQuery('[name="' + field.source + '"]:first').change().blur();
		}
	}, 'JSON');
};

vp.items_binding_event = function(ids, idx, field, func, parent, thecase)
{
	var change    = ['vp-select', 'vp-checkbox', 'vp-checkimage', 'vp-radiobutton', 'vp-radioimage', 'vp-multiselect', 'vp-toggle', 'vp-upload'],
	    typing    = ['vp-textbox', 'vp-slider', 'vp-color', 'vp-date'],
	    name      = vp.thejqname(ids[idx], thecase),
	    dest_type = jQuery(vp.thejqid(ids[idx], thecase)).attr('data-vp-type');

	if(jQuery.inArray(dest_type, change) !== -1 )
	{
		jQuery(parent).delegate(name, 'change', function(){vp.items_binding_action(ids, field, func, thecase);});
	}
	else if(jQuery.inArray(dest_type, typing) !== -1 )
	{
		jQuery(name).typing({
			stop: function(event, $elem){
				vp.items_binding_action(ids, field, func, thecase);
			},
			delay: 400
		});
	}
	else
	{
		jQuery(parent).delegate(name, 'change', function(){vp.binding_action(ids, field, func, thecase);});
	}
};

/*
 * =============================================================
 */

// vafpress dependencies related functions
vp.dependency_action =	function(ids, field, func) {

	var $source_tr = jQuery(vp.jqid(field.source)),
	    $source    = jQuery('[name="' + field.source + '"]'),
	    values     = [],
	    targets    = [];

	for (var i = 0; i < ids.length; i++)
	{
		targets.push(jQuery(vp.jqid(ids[i])));
		values.push(jQuery('[name="' + ids[i] + '"]').validationVal());
	}

	var data = {
		action   : 'vp_ajax_wrapper',
		func     : func,
		params   : values
	};

	// get loader
	jQuery.each(targets, function(idx, val){
		var $loader = val.find('.vp-js-bind-loader');
		$loader.vp_fadeIn();
	});

	jQuery.post(ajaxurl, data, function(response) {
		jQuery.each(targets, function(idx, val){
			var $loader = val.find('.vp-js-bind-loader');
			$loader.vp_fadeOut();
		});

		if (response.status)
		{
			if(response.data)
			{
				$source_tr.removeClass('vp-dep-inactive');
				$source_tr.vp_fadeIn();
			}
			else
			{
				$source_tr.addClass('vp-dep-inactive');
				$source_tr.vp_fadeOut();
			}
		}
	}, 'JSON');
};

vp.dependency_event = function(ids, idx, field, func, parent){

	var change    = ['vp-select', 'vp-checkbox', 'vp-checkimage', 'vp-radiobutton', 'vp-radioimage', 'vp-multiselect', 'vp-toggle', 'vp-upload'],
	    typing    = ['vp-textbox', 'vp-slider', 'vp-color', 'vp-date'],
	    name      = vp.thejqname(ids[idx], 'option'),
	    dest_type = jQuery(vp.thejqid(ids[idx], 'option')).attr('data-vp-type');

	if(jQuery.inArray(dest_type, change) !== -1 )
	{
		jQuery(parent).delegate(name, 'change', function(){vp.dependency_action(ids, field, func);});
	}
	else if(jQuery.inArray(dest_type, typing) !== -1 )
	{
		jQuery(name).typing({
			stop: function(event, $elem){
				vp.dependency_action(ids, field, func);
			},
			delay: 400
		});
	}
	else
	{
		jQuery(parent).delegate(name, 'change', function(){vp.binding_action(ids, field, func, thecase);});
	}
};

/*
 * =============================================================
 */

/**
 * =============================================================
 * Control Fields JS Trigering
 * =============================================================
 */

function theValidate(elem, options, $slider)
{
	var $this = jQuery(elem),
	    val = $this.val();
	if(val === '')
		return;
	if (!vp.validateNumeric('vp-textbox', val))
	{
		$this.val(options.min);
		$slider.slider('value', options.min);
	}
	if (val > options.max)
	{
		$this.val(options.max);
		$slider.slider('value', options.max);
	}
	else if (val < options.min)
	{
		$this.val(options.min);
		$slider.slider('value', options.min);
	}
	else
	{
		$slider.slider('value', $this.val());
	}
}

vp.init_slider = function($elements)
{
	if (jQuery.fn.slider)
	{
		$elements.each(function(i, el) {
			var $slider = jQuery(this),
			    options = jQuery(this).getDatas();
			options = vp.parseOpt(options.opt);
			options.range = 'min';
			options.slide = function(event, ui) {
				$slider.prev('.slideinput').val(ui.value);
				$slider.prev('.slideinput').trigger('keypress');
				$slider.prev('.slideinput').trigger('keyup');
			};
			$slider.slider(options);

			$slider.prev('.slideinput').keypress(function(e) {
				var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
				if (e.altKey || e.ctrlKey || e.shiftKey)
					return true;
				if (jQuery.inArray(charCode, [45, 46, 8, 0]) != -1 || (charCode >= 48 && charCode <= 57) )
					return true;
				return false;
			})
			.blur(function(e){
				theValidate(this, options, $slider);
				$slider.prev('.slideinput').keypress().keyup();
			})
		});
	}
};


vp.upload_callback = function() {};

if ( vp_wp.use_new_media_upload )
{
	var _orig_send_attachment = wp.media.editor.send.attachment,
	    _orig_send_link       = wp.media.editor.send.link,
	    _orig_send_to_editor  = window.send_to_editor;

	vp.upload_callback = function(e) {
		var $this    = jQuery(this),
		    $input   = $this.parent('.buttons').prev('input'),
		    $preview = $this.parent('.buttons').siblings('.image').find('img');

		// handler for attachment
		wp.media.editor.send.attachment = function(props, attachment) {

			$input.val(attachment.url);
			$input.trigger('change');

			if(attachment.type === 'image')
				$preview.attr('src', attachment.url);
			else
				$preview.attr('src', attachment.icon);

			wp.media.editor.send.attachment = _orig_send_attachment;
			window.send_to_editor = _orig_send_to_editor;
		};

		// handler for link
		window.send_to_editor = function(html) {
			if (html !== '')
			{
				var info = get_url_info(html);
				$input.val(info.imgurl);
				$input.trigger('change');
				$preview.attr('src', info.iconurl);
			}
			window.send_to_editor = _orig_send_to_editor;
			wp.media.editor.send.attachment = _orig_send_attachment;
		};
		wp.media.editor.open($this);
		return false;
	};
}
else
{
	var _orig_send_to_editor = window.send_to_editor;

	vp.upload_callback = function(e) {
		var _custom_media = true,
		       $input     = jQuery(this).parent('.buttons').prev('input'),
		       $preview   = jQuery(this).parent('.buttons').siblings('.image').find('img');

		tb_show('Upload Image', 'media-upload.php?&post_id=0&referer=vafpress&TB_iframe=true');

		window.send_to_editor = function(html) {
			if (html !== '')
			{
				var info = get_url_info(html);
				$input.val(info.imgurl);
				$input.trigger('change');
				$preview.attr('src', info.iconurl);
			}
			window.send_to_editor = _orig_send_to_editor;
			tb_remove();
		};
		return false;
	};
}

function get_url_info(html)
{
	var ext, type, imgurl, iconurl, $el = jQuery(html);
	if ($el.prop('tagName') == 'A')
	{
		imgurl  = jQuery(html).attr('href');
		ext     = vp.get_url_extension(imgurl);
		type    = vp.wp_ext2type(ext);
		iconurl = imgurl;
		if(type !== 'image')
		{
			iconurl = vp_wp.wp_include_url + 'images/crystal/' + type + '.png' ;
		}
	}
	else if($el.prop('tagName') == 'IMG')
	{
		imgurl = jQuery(html).attr('src');
		iconurl = imgurl;
	}
	return {imgurl: imgurl, iconurl: iconurl};
}

vp.remove_upload_callback = function(e) {
	var $this    = jQuery(this),
	    $input   = $this.parent('.buttons').prev('input'),
	    $preview = $this.parent('.buttons').siblings('.image').find('img');

	$input.val('');
	$preview.attr('src', '');
};

jQuery(document).on('click', '.vp-js-upload', vp.upload_callback);
jQuery(document).on('click', '.vp-js-remove-upload', vp.remove_upload_callback);

vp.init_colorpicker = function($elements)
{
	if (jQuery.fn.colorpicker)
	{
		if($elements.length <= 0)
			return;
		$elements.each(function() {
			var $colorpicker  = jQuery(this),
			    options = jQuery(this).getDatas();

			options = vp.parseOpt(options.opt);

			$colorpicker.colorpicker({
				format: options.format
			}).on('changeColor', function(ev){
				var color;
				if(options.format == 'hex')
				{
					color = ev.color.toHex();
				}
				else if(options.format == 'rgba')
				{
					color = ev.color.toRGB();
					color = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ',' + color.a + ')';
				}
				else if(options.format == 'rgb')
				{
					color = ev.color.toRGB();
					color = 'rgb(' + color.r + ',' + color.g + ',' + color.b + ')';
				}
				$colorpicker.prev('label').find('span').css('background-color', color);
			}).on('blur', function(ev){
				$colorpicker.prev('label').find('span').css('background-color', $colorpicker.val());
				$colorpicker.keypress().keyup();
			});
		});
	}
};

vp.init_datepicker = function($elements)
{
	if (jQuery.fn.datepicker)
	{
		if($elements.length <= 0)
			return;
		$elements.each(function() {
			var options = jQuery(this).getDatas();
			options     = vp.parseOpt(options.opt);
			options.onSelect = function(){
				jQuery(this).trigger('keypress');
				jQuery(this).trigger('keyup');
				jQuery(this).trigger('blur');
			};
			jQuery(this).datepicker(options);
			jQuery(this).datepicker('setDate', options.value);
		});
	}
};

vp.init_controls = function($parent)
{
	// init date picker
	vp.init_datepicker($parent.find('.vp-js-datepicker'));
	vp.init_fontawesome_chooser($parent.find('.vp-js-fontawesome'));
	vp.init_select2($parent.find('.vp-js-select2'));
	vp.init_sorter($parent.find('.vp-js-sorter'));
	vp.init_colorpicker($parent.find('.vp-js-colorpicker'));
	vp.init_slider($parent.find('.vp-js-slider'));
	vp.init_ace_editor($parent.find('.vp-js-codeeditor'));
	vp.init_wpeditor($parent.find('.vp-js-wpeditor'));
};

// Fontawesome Chooser
vp.init_fontawesome_chooser = function($elements)
{
	if (jQuery.fn.select2)
	{
		if($elements.length <= 0)
			return;
		var format = function vp_fontawesome_chooser_format(icon){
			return '<span class="fontawesome"><i class="fa ' + icon.id + '"></i>' + icon.text + '</span>';
		};
		$elements.select2({
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; },
			allowClear: true,
			placeholder: vp_wp.ctrl_msg.fac_placeholder
		});
	}
};

// Select2
vp.init_select2 = function($elements)
{
	if (jQuery.fn.select2)
	{
		if($elements.length <= 0)
			return;
		$elements.select2({allowClear: true, placeholder: vp_wp.ctrl_msg.select2_placeholder});
	}
};

// Sorter
vp.init_sorter = function($elements)
{
	if (jQuery.fn.select2Sortable)
	{
		if($elements.length <= 0)
			return;

		$elements.each(function(i, el) {
			var $el     = jQuery(el),
			    options = $el.getDatas();
			options = vp.parseOpt(options.opt);
			$el.select2(options).select2Sortable({bindOrder: 'sortableStop'});
		});

	}
};

// Tipsy
vp.init_tipsy = function()
{
	if (jQuery.fn.tipsy)
	{
		jQuery('.vp-js-tipsy.description').tipsy({ live: true, gravity : 'e' });
		jQuery('.vp-js-tipsy.slideinput').tipsy({ live: true, trigger : 'focus' });
		jQuery('.vp-js-tipsy.image-item').tipsy({ live: true });
	}
};
vp.init_tipsy();

// Init Sorter
vp.init_ace_editor = function($elements)
{
	if(window.ace !== 'undefined')
	{
		if($elements.length <= 0)
			return;
		$elements.each(function() {

			var editor   = ace.edit(jQuery(this).get(0));
			var textarea = jQuery(this).prev();
			var options  = jQuery(this).getDatas();

			options      = vp.parseOpt(options.opt);

			// set theme
			editor.setTheme("ace/theme/" + options.theme);
			// set language mode if specified
			if( options.mode ) {
				editor.getSession().setMode("ace/mode/" + options.mode);
			}
			editor.getSession().setUseWrapMode( true );
			editor.setShowPrintMargin( false );

			editor.getSession().setValue(textarea.val());
			editor.getSession().on('change', function(){
				textarea.val(editor.getSession().getValue());
			});
			textarea.on('change', function(){
				editor.getSession().setValue(textarea.val());
			});

		});
	}
};

// Init WP TinyMCE Editor
if(typeof window.KIA_metabox !== 'undefined')
{
	KIA_metabox.mediaButtons();
}
vp.init_wpeditor = function($elements)
{
	if(typeof window.KIA_metabox !== 'undefined')
	{
		if($elements.length <= 0)
			return;
		KIA_metabox.runTinyMCE($elements);
	}
};
vp.tinyMCE_save = function()
{
	if(typeof window.tinyMCE !== 'undefined')
	{
		tinyMCE.triggerSave(false, true);
	}
};