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
/* A bit modification to works with Vafpress
/*-----------------------------------------------------------------------------------*/

var KIA_metabox;

;(function ($) {

	KIA_metabox = {

/*-----------------------------------------------------------------------------------*/
/* Launch tinyMCE, handle tinyMCE on repeating fields
/*-----------------------------------------------------------------------------------*/
	tinyMCE: function() {

		//run our tinyMCE script on textareas at load
		KIA_metabox.runTinyMCE();

		//create a div to bind to
		if ( ! $.wpalchemy ) {
			$.wpalchemy = $('<div/>').attr('id','wpalchemy').appendTo('body');
		}

		//run our tinyMCE script on textareas when copy is made
		$(document.body).on('wpa_copy', $.wpalchemy, function() {
			// KIA_metabox.runTinyMCE();
		});
	},

/*-----------------------------------------------------------------------------------*/
/* Repeatable TinyMCE-enhanced textareas
/*-----------------------------------------------------------------------------------*/

	runTinyMCE: function(textarea) {

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
				plugins:"safari,inlinepopups,spellchecker,paste,wordpress,tabfocus"
				// width : "540"
			};

			//store the default settings
			try {
				tinyMCEdefaultConfig =  $.extend(true, {}, tinyMCE.settings);

				//tweak the setting just a litte to set the height and to add an HTML code button (since toggling editors is crazy difficult)
				tinyMCEdefaultConfig.height = "250";
				tinyMCEdefaultConfig.theme_advanced_buttons1 = tinyMCEdefaultConfig.theme_advanced_buttons1 + ',|,code';

			} catch(e) {
				tinyMCEdefaultConfig = tinyMCEminConfig;
			}

			//give each a unique ID so we can apply TinyMCE to each 
			var id = textarea.attr('id');

			try {
				//if the customEditor div has the minimal class, serve up the minimal tinyMCE configuration
				if(textarea.parent().hasClass('minimal')){
					tinyMCE.settings = tinyMCEminConfig;
				} else {
					tinyMCE.settings = tinyMCEdefaultConfig;
				}

				tinyMCE.execCommand('mceAddControl', false, id);
			} catch(e){}


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

	} //end mediaButtons

	}; // End KIA_metabox Object // Don't remove this, or the sky will fall on your head.

})(jQuery);

// jQuery insertAtCaret plugin
// http://stackoverflow.com/questions/946534/insert-text-into-textarea-with-jquery#answer-2819568
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
