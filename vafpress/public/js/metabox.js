;(function($) {

	// Select2
	if ($.fn.select2) jQuery('.vp-metabox .vp-wpa-group:not(.tocopy) .vp-js-select2, .vp-meta-single .vp-js-select2').select2({
		allowClear: true, placeholder: "Select option(s)"
	});
	if ($.fn.select2Sortable) jQuery('.vp-metabox .vp-wpa-group:not(.tocopy) .vp-js-sorter, .vp-meta-single .vp-js-sorter').select2().select2Sortable();

	vp.is_multianswer = function(type){
		var multi = ['vp-checkbox', 'vp-checkimage', 'vp-multiselect'];
		if(jQuery.inArray(type, multi) !== -1 )
		{
			return true;
		}
		return false;
	};

	// image controls event bind
	vp.custom_check_radio_event(".vp-metabox", ".vp-field.vp-checkimage .field .input label");
	vp.custom_check_radio_event(".vp-metabox", ".vp-field.vp-radioimage .field .input label");

	// Pool validation rules
	var validation = [];
	$('.vp-field').each(function(i) {
		var $field = $(this);

		if($field.parents('.wpa_group').hasClass('tocopy'))
			return;

		var name   = $field.attr('id'),
			rules  = $field.attr('data-vp-validation'),
			type   = $field.getDatas().type;

		rules && validation.push({name: name, rules: rules, type: type});
	});

	// Bind event to WP publish button to process metabox validation
	$('#post').on( 'submit', function(e){

		var submitter = $("input[type=submit][clicked=true]"),
		    action    = submitter.val(),
		    errors    = 0;

		$('.vp-field').removeClass('vp-error');
		$('.validation-msg.vp-error').remove();
		$('.vp-metabox-error').remove();

		errors = vp.fields_validation_loop(validation);

		if(errors > 0)
		{
			$notif = $('<span class="vp-metabox-error vp-js-tipsy" original-title="' + errors + ' error(s) found in metabox"></span>');

			if(action === 'Save Draft')
			{
				$('#minor-publishing-actions .spinner, #minor-publishing-actions .ajax-loading').hide();
				$notif.tipsy();
				$notif.insertAfter('#minor-publishing-actions .spinner, #minor-publishing-actions .ajax-loading');
				$('#save-post').prop('disabled', false).removeClass('button-disabled');
			}
			else if(action === 'Publish' || action === 'Update')
			{
				$('#publishing-action .spinner, #publishing-action .ajax-loading').hide();
				$notif.tipsy();
				$notif.insertAfter('#publishing-action .spinner, #publishing-action .ajax-loading');
				$('#publish').prop('disabled', false).removeClass('button-primary-disabled');
			}

			var margin_top = Math.ceil((submitter.outerHeight() - $notif.height()) / 2);
			if(margin_top > 0)
				$notif.css('margin-top', margin_top);
			e.preventDefault();
		}

	});


	$("#post input[type=submit]").click(function() {
		$("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
		$(this).attr("clicked", "true");
	});


	// Bindings
	var bindings = [];
	$('.vp-field[data-vp-bind]').each(function(idx, el){
		var bind = $(el).attr('data-vp-bind'),
			type = $(el).getDatas().type,
			name = $(el).attr('id');
			bind && bindings.push({bind: bind, type: type, source: name});
	});

	function process_binding(bindings)
	{
		for (var i = 0; i < bindings.length; i++)
		{
			var field   = bindings[i];
			var temp    = field.bind.split('|');
			var func    = temp[0];
			var dest    = temp[1];
			var ids     = [];

			var prefix  = '';
			prefix      = field.source.replace('[]', '');
			prefix      = prefix.substring(0, prefix.lastIndexOf('['));

			dest = dest.split(',');

			for (var j = 0; j < dest.length; j++)
			{
				dest[j] = prefix + '[' + dest[j] + ']';
				ids.push(dest[j]);
			}

			for (j = 0; j < ids.length; j++)
			{
				vp.binding_event(ids, j, field, func, '.vp-metabox', 'metabox');
			}
		}
	}

	process_binding(bindings);

	// dependency
	// Get array of dependencies single field or group
	var dependencies = [];
	$('.vp-field[data-vp-dependency]').each(function(idx, el){
		var dep  = $(el).attr('data-vp-dependency'),
			type = $(el).getDatas().type,
			name = $(el).attr('id');

		dep && dependencies.push({dep: dep, type: 'field', source: name});
	});
	$('.vp-meta-group[data-vp-dependency]').each(function(idx, el){
		var dep  = $(el).attr('data-vp-dependency'),
			type = $(el).getDatas().type,
			name = $(el).attr('id');

		dep && dependencies.push({dep: dep, type: 'section', source: name});
	});

	function process_dependency(dependencies)
	{
		for (var i = 0; i < dependencies.length; i++)
		{
			var field  = dependencies[i];
			var temp   = field.dep.split('|');
			var func   = temp[0];
			var dest   = temp[1];
			var ids    = [];
			var prefix = '';

			if(field.type === 'field')
			{
				// strip [] (which multiple option field has)
				prefix = field.source.replace('[]', '');
				prefix = prefix.substring(0, prefix.lastIndexOf('['));
			}
			else if(field.type === 'section')
			{
				// get the closest 'postbox' class parent id
				prefix = jQuery(vp.jqid(field.source)).parents('.postbox').attr('id');
				// strip the '_metabox'
				prefix = prefix.substring(0, prefix.lastIndexOf('_'));
			}

			dest = dest.split(',');

			for (var j = 0; j < dest.length; j++)
			{
				dest[j] = prefix + '[' + dest[j] + ']';
				ids.push(dest[j]);
			}

			for (j = 0; j < ids.length; j++)
			{
				vp.dependency_event(ids, j, field, func, '.vp-metabox');
			}
		}
	}
	process_dependency(dependencies);

	$.wpalchemy.on('wpa_copy', function(event, clone){

		bindings      = [];
		dependencies  = [];
		clone.find('.vp-field[data-vp-bind]').each(function(idx, el){
			var bind = $(el).attr('data-vp-bind'),
				type = $(el).getDatas().type,
				name = $(el).attr('id');

			bind && bindings.push({bind: bind, type: type, source: name});
		});
		clone.find('.vp-field[data-vp-dependency]').each(function(idx, el){
			var dep  = $(el).attr('data-vp-dependency'),
				type = $(el).getDatas().type,
				name = $(el).attr('id');

			dep && dependencies.push({dep: dep, type: 'field', source: name});
		});
		clone.find('.vp-field[data-vp-validation]').each(function(idx, el){
			var $field = $(el),
				name   = $field.attr('id'),
				rules  = $field.attr('data-vp-validation'),
				type   = $field.getDatas().type;

			rules && validation.push({name: name, rules: rules, type: type});
		});

		// Re-init Select2
		if ($.fn.select2) clone.find('.vp-js-select2').select2({allowClear: true, placeholder: "Select option(s)"});
		if ($.fn.select2Sortable) clone.find('.vp-js-sorter').select2().select2Sortable();

		process_binding(bindings);
		process_dependency(dependencies);
	});

}(jQuery));