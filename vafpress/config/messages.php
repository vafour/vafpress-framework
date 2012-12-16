<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value need to be Alphabet', 'vafpress'),
		'alphanumeric' => __('Value need to be Alphanumeric', 'vafpress'),
		'numeric'      => __('Value need to be Numeric', 'vafpress'),
		'email'        => __('Value need to be Valid Email', 'vafpress'),
		'url'          => __('Value need to be Valid URL', 'vafpress'),
		'maxlength'    => __('Length need to be less than {0} characters', 'vafpress'),
		'minlength'    => __('Length need to be more than {0} characters', 'vafpress'),
		'maxselected'  => __('Select no more than {0} items', 'vafpress'),
		'minselected'  => __('Select at least {0} items', 'vafpress'),
		'required'     => __('This is Required', 'vafpress'),
	),

	/**
	 * Import / Export Messages
	 */
	'impexp' => array(
		'import_success' => __('Import success, option page will be refreshed..', 'vafpress'),
		'import_failed'  => __('Import failed', 'vafpress'),
		'export_success' => __('Export success, copy the serialized options', 'vafpress'),
		'export_failed'  => __('Export failed', 'vafpress'),
	),

);

/**
 * EOF
 */