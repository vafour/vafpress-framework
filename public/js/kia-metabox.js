/*-----------------------------------------------------------------------------------*/
/* KIA Metabox scripts
/*
/* upload media buttons, sort, repeatable tinyMCE fields 
/* requires jquery 1.7
/* tested on WordPress 3.3.1
/*
/* © Kathy Darling http://www.kathyisawesome.com
/* 2012-03-07.
/*
/* A bit modification to work with Vafpress (http://vafpress.com)
/*-----------------------------------------------------------------------------------*/

var KIA_metabox, tinyMCEbackupConfig = null;

;(function ($) {

	KIA_metabox = {

/*-----------------------------------------------------------------------------------*/
/* Repeatable TinyMCE-enhanced textareas
/*-----------------------------------------------------------------------------------*/

	runTinyMCE: function($textareas) {

		// some settings for a more minimal tinyMCE editor
		tinyMCEminConfig = {
			theme : "advanced",
			skin:"wp_theme",
			mode : "none",
			language : "en",
			theme_advanced_resizing:"1",
			width  :"100%",
			height : "250",
			theme_advanced_layout_manager : "SimpleLayout",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_buttons1:"styleselect,formatselect,bold,italic,strikethrough,underline,|,link,unlink,|,forecolor,|undo,redo,|,code",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_statusbar_location:"",
			remove_linebreaks: true,
			plugins:"safari,inlinepopups,spellchecker,paste,wordpress,tabfocus"
		};

		if(tinyMCEbackupConfig === null)
		{
			tinyMCEbackupConfig  =  $.extend(true, {}, tinyMCE.settings);
		}

		//store the default settings
		try {
			// tinyMCEdefaultConfig = $.extend(true, {}, tinyMCE.settings);
			tinyMCEdefaultConfig = $.extend(true, {}, tinyMCEbackupConfig);

			//tweak the setting just a litte to set the height and to add an HTML code button (since toggling editors is crazy difficult)
			tinyMCEdefaultConfig.height = "250";
			tinyMCEdefaultConfig.theme_advanced_buttons1 = tinyMCEdefaultConfig.theme_advanced_buttons1 + ',|,code';

		} catch(e) {
			tinyMCEdefaultConfig = tinyMCEminConfig;
		}

		$textareas.each(function(){

			//give each a unique ID so we can apply TinyMCE to each 
			var id = $(this).attr('id');
			try {
				//if the customEditor div has the minimal class, serve up the minimal tinyMCE configuration
				if($(this).parent().hasClass('minimal')){
					tinyMCE.settings = tinyMCEminConfig;
				} else {
					tinyMCE.settings = tinyMCEdefaultConfig;
				}

				var options  = $(this).getDatas();
				options      = vp.parseOpt(options.opt);
				options.use_external_plugins = options.use_external_plugins ? true : false;

				var plugins = tinyMCE.settings.plugins;
				if(options.use_external_plugins === false)
				{
					plugins = plugins.replace(/\-(.*?)(,|$)+?/gm, '');
				}
				else
				{
					var dep = options.disabled_externals_plugins,
					    dip = options.disabled_internals_plugins,
						reg;

					dep = dep.trim();
					dep = dep.split(/[\s,]+/).join("|");
					if(dep !== "")
					{
						reg = new RegExp('\\-(' + dep + ')(,|$)+?', 'gmi');
						plugins = plugins.replace(reg, '');
					}

					dip = dip.trim();
					dip = dip.split(/[\s,]+/).join("|");
					if(dip !== "")
					{
						reg = new RegExp('\\-(' + dip + ')(,|$)+?', 'gmi');
						plugins = plugins.replace(reg, '');
					}
				}
				tinyMCE.settings.plugins = plugins;

				tinyMCE.execCommand('mceAddControl', false, id);
			} catch(e){}

		});
		// restore default settings
		tinyMCE.settings = $.extend(true, {}, tinyMCEbackupConfig);

	} , //end runTinyMCE text areas 

/*-----------------------------------------------------------------------------------*/
/* Custom Media Upload Buttons for tinyMCE textareas
/*-----------------------------------------------------------------------------------*/

	mediaButtons: function() {

		$('body').on('click','.custom_upload_buttons a',function(){
			textarea = $(this).closest('.customEditor').find('textarea');
			mceID = textarea.attr('id');
			kia_backup = window.send_to_editor; // backup the original 'send_to_editor' function
			window.send_to_editor = window.send_to_editor_clone;
		});

		//borrow the send to editor function
		window.send_to_editor_clone = function(html){

			try {
				tinyMCE.execInstanceCommand(mceID, 'mceInsertContent', false, html);
			} catch(e) {
				$(textarea).insertAtCaret(html);
			}

			tb_remove();

			// restore the default behavior
			window.send_to_editor = kia_backup;
		};

	}, //end mediaButtons

/*-----------------------------------------------------------------------------------*/
/* Meta Fields Sorting
/*-----------------------------------------------------------------------------------*/

	sortable: function() {

		var textareaIDs = [];
		$('.wpa_loop.vp-sortable').sortable({
			items: '>.wpa_group',
			handle: '.vp-wpa-group-heading',
			axis: 'y',
			opacity: 0.5,
			tolerance: 'pointer',
			start: function(event, ui) { // turn TinyMCE off while sorting (if not, it won't work when resorted)
				textareaIDs = [];
				vp.tinyMCE_save();
				$(ui.item).find('.customEditor textarea').each(function(){
					if($(this).parents('.tocopy').length <= 0)
					{
						try { tinyMCE.execCommand('mceRemoveControl', false, this.id); } catch(e){}
						textareaIDs.push(vp.jqid(this.id));
					}
				});
			},
			stop: function(event, ui) { // re-initialize TinyMCE when sort is completed
				for (var i = textareaIDs.length - 1; i >= 0; i--) {
					var $textarea = $(textareaIDs[i]);
					$textarea.val(switchEditors.wpautop($textarea.val()));
				}
				textareaIDs = textareaIDs.join(", ");
				try {
					KIA_metabox.runTinyMCE($(textareaIDs));
					vp.tinyMCE_save();
					for (var i = textareaIDs.length - 1; i >= 0; i--) {
						var $textarea = $(textareaIDs[i]);
						$textarea.val(switchEditors.pre_wpautop($textarea.val()));
					}
				} catch(e){}
				$(this).find('.update-warning').show();
			}
		});
	} //end of sortable


	}; // End KIA_metabox Object // Don't remove this, or the sky will fall on your head.

})(jQuery);

// jQuery insertAtCaret plugin
// http://stackoverflow.com/questions/946534/insert-text-into-textarea-with-jquery#answer-2819568
if(!jQuery.fn.insertAtCaret)
{
	jQuery.fn.extend({
		insertAtCaret: function(myValue){
			return this.each(function(i) {
				if (document.selection) {
					//For browsers like Internet Explorer
					this.focus();
					sel = document.selection.createRange();
					sel.text = myValue;
					this.focus();
				}
				else if (this.selectionStart || this.selectionStart == '0') {
					//For browsers like Firefox and Webkit based
					var startPos = this.selectionStart;
					var endPos = this.selectionEnd;
					var scrollTop = this.scrollTop;
					this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
					this.focus();
					this.selectionStart = startPos + myValue.length;
					this.selectionEnd = startPos + myValue.length;
					this.scrollTop = scrollTop;
				} else {
					this.value += myValue;
					this.focus();
				}
			});
		}
	});
}