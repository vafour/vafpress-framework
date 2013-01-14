;(function($) {

	$.getScript(vp_wp.public_url + "/js/shared.js", function(data, textStatus, jqxhr) {

		// Chosen
		if (jQuery().chosen)
		{
			$('.vp-js-chosen').chosen();
	 	}

	});
}(jQuery));