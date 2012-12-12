<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value need to be Alphabet', VP_TEXTDOMAIN),
		'alphanumeric' => __('Value need to be Alphanumeric', VP_TEXTDOMAIN),
		'numeric'      => __('Value need to be Numeric', VP_TEXTDOMAIN),
		'email'        => __('Value need to be Valid Email', VP_TEXTDOMAIN),
		'url'          => __('Value need to be Valid URL', VP_TEXTDOMAIN),
		'maxlength'    => __('Length need to be less than {0} characters', VP_TEXTDOMAIN),
		'minlength'    => __('Length need to be more than {0} characters', VP_TEXTDOMAIN),
		'maxselected'  => __('Select no more than {0} items', VP_TEXTDOMAIN),
		'minselected'  => __('Select at least {0} items', VP_TEXTDOMAIN),
		'required'     => __('This is Required', VP_TEXTDOMAIN),
	),

	/**
	 * Import / Export Messages
	 */
	'impexp' => array(
		'import_success' => __('Import success, option page will be refreshed..', VP_TEXTDOMAIN),
		'import_failed'  => __('Import failed', VP_TEXTDOMAIN),
		'export_success' => __('Export success, copy the serialized options', VP_TEXTDOMAIN),
		'export_failed'  => __('Export failed', VP_TEXTDOMAIN),
	),

);

/**
 * EOF
 */