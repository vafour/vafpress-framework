;(function($) {

	$.getScript(vp_wp.public_url + "/js/shared.js", function(data, textStatus, jqxhr) {

		// Chosen
		if (jQuery().chosen)
		{
			jQuery('.vp-metabox .vp-wpa-group:not(.tocopy) .vp-js-chosen, .vp-meta-single .vp-js-chosen').chosen();
		}

		vp.is_multianswer = function(type){
			var multi = ['vp-checkbox', 'vp-checkimage', 'vp-multiselect'];
			if(jQuery.inArray(type, multi) !== -1 )
			{
				return true;
			}
			return false;
		};

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

		jQuery('.vp-js-codeeditor').each(function() {

			var editor   = ace.edit(jQuery(this).get(0));
			var textarea = jQuery(this).prev();

			var options = jQuery(this).getDatas();
			options     = vp.parseOpt(options.opt);

			editor.setTheme("ace/theme/" + options.theme);
			editor.getSession().setMode("ace/mode/" + options.mode);
			editor.getSession().setUseWrapMode( true );
            editor.setShowPrintMargin( false );

			editor.getSession().setValue(textarea.val());
			editor.getSession().on('change', function(){
				var editorDoc = editor.getSession().getDocument();
				textarea.text(editor.getSession().getValue());
			});

			// var options = jQuery(this).getDatas();
			// options     = vp.parseOpt(options.opt);
			// options.onSelect = function(){
			//	jQuery(this).trigger('keypress');
			//	jQuery(this).trigger('keyup');
			//	jQuery(this).trigger('blur');
			// };
			// jQuery(this).datepicker(options);
			// jQuery(this).datepicker('setDate', options.value);
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

		$('[class*=docopy-]').click(function(e)
		{
			var p         = $(this).parents('.postbox'); /*wp*/
			var the_name  = $(this).attr('class').match(/docopy-([a-zA-Z0-9_-]*)/i)[1];
			var the_group = $('.wpa_group-'+ the_name +'.tocopy', p).first().prev();
			bindings      = [];
			dependencies  = [];
			the_group.find('.vp-field[data-vp-bind]').each(function(idx, el){
				var bind = $(el).attr('data-vp-bind'),
					type = $(el).getDatas().type,
					name = $(el).attr('id');

				bind && bindings.push({bind: bind, type: type, source: name});
			});
			the_group.find('.vp-field[data-vp-dependency]').each(function(idx, el){
				var dep  = $(el).attr('data-vp-dependency'),
					type = $(el).getDatas().type,
					name = $(el).attr('id');

				dep && dependencies.push({dep: dep, type: 'field', source: name});
			});
			if ($.fn.chosen) the_group.find('.vp-js-chosen').chosen();
			process_binding(bindings);
			process_dependency(dependencies);
		});


	});
}(jQuery));