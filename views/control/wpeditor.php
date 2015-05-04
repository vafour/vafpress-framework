
<?php
	$editor_settings=array(
			'wpautop' 			=> true,
			'media_buttons'	=> true,
			'textarea_name' => $name,
			'textarea_rows' => 10,
			'quicktags'			=> true,
			'drag_drop_upload' => true
	);
	/**
	* Remove [, ], {, } (brackets), - (hypen) from Editor ID
	* @since 3.9
	* TinyMCE editor IDs cannot have brackets.
	*/

	$id=str_replace(array("[","]","-","{","}"), "", $name);
	$id=$id.'_ce';

?>
<?php wp_editor( $value, $id, $editor_settings; ?> 
