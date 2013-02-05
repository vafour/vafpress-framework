;(function($) {

	$.getScript(vp_wp.public_url + "/js/shared.js", function(data, textStatus, jqxhr) {

		// Chosen
		if (jQuery().chosen)
		{
			jQuery('.vp-metabox .vp-wpa-group:not(.tocopy) .vp-js-chosen, .vp-meta-single .vp-js-chosen').chosen();
	 	}

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

				for (var j = 0; j < ids.length; j++)
				{
					vp.binding_event(ids, j, field, func, '.vp-metabox');
				}
			}
		}

		process_binding(bindings);

		$('[class*=docopy-]').click(function(e)
		{
			var p = $(this).parents('.postbox'); /*wp*/
			var the_name = $(this).attr('class').match(/docopy-([a-zA-Z0-9_-]*)/i)[1];
			var the_group = $('.wpa_group-'+ the_name +'.tocopy', p).first().prev();
		 	bindings = [];
			the_group.find('.vp-field[data-vp-bind]').each(function(idx, el){
	 			var bind = $(el).attr('data-vp-bind'),
					type = $(el).getDatas().type,
					name = $(el).attr('id');

				bind && bindings.push({bind: bind, type: type, source: name});
		 	});
		 	if ($.fn.chosen) the_group.find('.vp-js-chosen').chosen();
		 	process_binding(bindings);
		});

	});
}(jQuery));