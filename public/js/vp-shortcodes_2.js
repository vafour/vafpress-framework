
(function($){
	console.log(typeof window.sg);
	if( typeof window.sg === 'undefined' )
	{
		for (var i = 0; i < vp_sg.length; i++){

			window.sg = 'step1';

			var sg     = vp_sg[i],
			    sg_cmd = sg.name + '_cmd';

			console.log('tinymce.plugins.' + sg.name);
			console.log(sg_cmd);
			tinymce.create('tinymce.plugins.' + sg.name, {
				init: function(ed, url) {
					var cmd_cb = function(name) {
						return function() {
							$('#' + name + '_modal').reveal();
							$('#' + name + '_modal').unbind('vp_insert_shortcode.vp_tinymce');
							$('#' + name + '_modal').bind('vp_insert_shortcode.vp_tinymce', function(event, code) {
								ed.selection.setContent(code);
								$(ed.getElement()).insertAtCaret(code);
							});
						}
					}
					ed.addCommand(sg_cmd, cmd_cb(sg.name));
					ed.addButton(sg.name, {title: sg.button_title, cmd: sg_cmd, image: sg.main_image});
				},
				getInfo: function() {
					return {
						longname: 'Vafpress Framework',
						author  : 'Vafpress'
					};
				}
			});

		}
	}

})(jQuery);

if( typeof window.sg !== 'undefined' && window.sg == 'step1' )
{
	for (var i = 0; i < vp_sg.length; i++){
		var sg     = vp_sg[i];
		console.log(sg.name);
		console.log(tinymce.plugins);
		console.log(tinymce.plugins[sg.name]);
		tinymce.PluginManager.add(sg.name, tinymce.plugins[sg.name]);
	}
	window.sg = 'step2';
}


// tinymce.PluginManager.add('vp_sc_button', tinymce.plugins.button);
// tinymce.PluginManager.add('vp_sc_button1', tinymce.plugins.button);
// tinymce.PluginManager.add('vp_sc_button2', tinymce.plugins.button);
// tinymce.PluginManager.add('vp_sc_button3', tinymce.plugins.button);