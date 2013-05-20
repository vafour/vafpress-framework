;(function($) {

	var validation   = [];
	var bindings     = [];
	var dependencies = [];

	function vp_init_fields($elements)
	{
		$elements.each(function(){
			if($(this).parents('.tocopy').length <= 0)
			{
				// init select2 and friends
				if ($.fn.select2)
					$(this).find('.vp-js-select2').select2({allowClear: true, placeholder: "Select option(s)"});
				if ($.fn.select2Sortable)
					$(this).find('.vp-js-sorter').select2().select2Sortable();
				vp.init_fontawesome_chooser($(this).find('.vp-js-fontawesome'));

				var id    = $(this).attr('id'),
					name  = $(this).attr('id'),
					rules = $(this).attr('data-vp-validation'),
					bind  = $(this).attr('data-vp-bind'),
					dep   = $(this).attr('data-vp-dependency'),
					type  = $(this).getDatas().type;

				// init validation
				rules && validation.push({name: id, rules: rules, type: type});
				// init binding
				if(typeof bind !== 'undefined' && bind !== false)
				{
					bind && bindings.push({bind: bind, type: type, source: id});
				}
				// init dependancies
				if(typeof dep !== 'undefined' && dep !== false)
				{
					dep && dependencies.push({dep: dep, type: 'field', source: id});
				}
			}
		});
	}

	function vp_init_groups($elements)
	{
		$elements.each(function(){
			if($(this).parents('.tocopy').length <= 0 && !$(this).hasClass('.tocopy'))
			{
				var dep  = $(this).attr('data-vp-dependency'),
					type = $(this).getDatas().type,
					id   = $(this).attr('id');
				if(typeof dep !== 'undefined' && dep !== false)
				{
					dep && dependencies.push({dep: dep, type: 'section', source: id});
				}
			}
		});
	}

	vp_init_fields(jQuery('.vp-metabox .vp-field'));
	vp_init_groups(jQuery('.vp-metabox .vp-meta-group'));

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
				var $source = jQuery(vp.jqid(field.source));
				if($source.parents('.wpa_group').length > 0)
				{
					prefix = jQuery(vp.jqid(field.source)).parents('.wpa_group').first().attr('id');
				}
				else
				{
					// get the closest 'postbox' class parent id
					prefix = jQuery(vp.jqid(field.source)).parents('.postbox').attr('id');
					// strip the '_metabox'
					prefix = prefix.substring(0, prefix.lastIndexOf('_'));
				}
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

		// delete tocopy hidden field
		clone.find('input[class="tocopy-hidden"]').first().remove();

		vp_init_fields(clone.find('.vp-field'));
		vp_init_groups(clone.find('.vp-meta-group'));

		console.log(bindings);
		console.log(dependencies);

		process_binding(bindings);
		process_dependency(dependencies);
	});

}(jQuery));